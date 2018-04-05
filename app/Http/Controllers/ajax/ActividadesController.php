<?php

namespace App\Http\Controllers\ajax;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ActividadCollection;
use App\Http\Resources\ActividadResource;
use Illuminate\Http\Request;
use App\Actividad;

use Illuminate\Support\Facades\DB;

class actividadesController extends BaseController
{
    /**
     * Devuelve un JSON de actividades
     *
     * @param int $items Cantidad de elementos en cada página
     * @return ActividadCollection
     */
    public function index(Request $request, $items=6)
    {
        return $this->filtrar($request, $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new ActividadResource(Actividad::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function filtrar(Request $request, $items)
    {
        $query = DB::table('Actividad')
            ->join('Tipo', 'Actividad.idTipo', '=', 'Tipo.idTipo')
            ->leftJoin('PuntoEncuentro', 'Actividad.idActividad', '=', 'PuntoEncuentro.idActividad');

        if($request->has('busqueda') && $request->busqueda == 'punto'){
            $query->join('atl_pais', 'PuntoEncuentro.idPais', '=', 'atl_pais.id')
                ->join('atl_provincias', 'PuntoEncuentro.idProvincia', '=', 'atl_provincias.id')
                ->join('atl_localidades', 'PuntoEncuentro.idLocalidad', '=', 'atl_localidades.id');
        } else { //por lugar de actividad
            $query->join('atl_pais', 'Actividad.idPais', '=', 'atl_pais.id')
                ->join('atl_provincias', 'Actividad.idProvincia', '=', 'atl_provincias.id')
                ->join('atl_localidades', 'Actividad.idLocalidad', '=', 'atl_localidades.id');
        }

        $query->selectRaw('distinct Actividad.*')
            ->orderBy('fechaInicio', 'desc');

        if ($request->has('categoria') && !is_null($request->categoria)) {
            $query->where('Tipo.idCategoria', $request->categoria);
        }

        if ($request->has('localidades') && !is_null($request->localidades) && !empty($request->localidades)) {
            $query->whereIn('atl_localidades.id', $request->localidades);
        }

        if ($request->has('tipos') && !is_null($request->tipos) && !empty($request->tipos)) {
            $query->whereIn('Tipo.idTipo', $request->tipos);
        }
        $actividades = $query->get();
        $actividades = Actividad::hydrate($actividades->toArray());

        if ($actividades->count() > 0) {
            foreach ($actividades as $actividad) {
                $resourceCollection[] = new ActividadResource($actividad);
            }
            return $this->paginate($resourceCollection, $items, $request->query());
        }
        return $actividades;

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function filtrarProvinciasYLocalidades(Request $request)
    {

        $default = 1; //Actividades en Asentamientos
        $categoria = $request->categoria ?? $default;

        $provincias = DB::table('Actividad')
            ->join('Tipo', 'Actividad.idTipo', '=', 'Tipo.idTipo')
            ->leftJoin('PuntoEncuentro', 'Actividad.idActividad', '=', 'PuntoEncuentro.idActividad');

        if($request->has('busqueda') && $request->busqueda == 'punto'){
            $provincias->join('atl_pais', 'PuntoEncuentro.idPais', '=', 'atl_pais.id')
                ->join('atl_provincias', 'PuntoEncuentro.idProvincia', '=', 'atl_provincias.id')
                ->join('atl_localidades', 'PuntoEncuentro.idLocalidad', '=', 'atl_localidades.id');
        } else { //por lugar de actividad
            $provincias->join('atl_pais', 'Actividad.idPais', '=', 'atl_pais.id')
                ->join('atl_provincias', 'Actividad.idProvincia', '=', 'atl_provincias.id')
                ->join('atl_localidades', 'Actividad.idLocalidad', '=', 'atl_localidades.id');
        }

        $provincias->where('Tipo.idCategoria', $categoria)
            ->orderBy('atl_provincias.Provincia', 'asc')
            ->orderBy('atl_localidades.Localidad', 'asc')
            ->selectRaw('distinct atl_provincias.id id_provincia, atl_provincias.Provincia, 
                                         atl_localidades.id id_localidad, atl_localidades.Localidad');

        if ($request->has('tipos') && !is_null($request->tipos) && !empty($request->tipos)) {
            $provincias->whereIn('Tipo.idTipo', $request->tipos);
        }

        $provincias = $provincias->get();

        $listProvincias = [];

        for ($i = 0; $i < count($provincias); $i++) {
            $idProvincia = (int)$provincias[$i]->id_provincia;
            $listProvincias[$idProvincia]['id_provincia'] = $idProvincia;
            $listProvincias[$idProvincia]['provincia'] = $provincias[$i]->Provincia;
            $listProvincias[$idProvincia]['localidades'][] =
                array('id_localidad' => $provincias[$i]->id_localidad,
                    'localidad' => $provincias[$i]->Localidad);
        }
        return $listProvincias;
    }
    public function filtrarTiposDeActividades(Request $request)
    {

        $default = 1; //Actividades en Asentamientos
        $categoria = $request->categoria ?? $default;

        $query = DB::table('Actividad')
            ->join('Tipo', 'Actividad.idTipo', '=', 'Tipo.idTipo')
            ->leftJoin('PuntoEncuentro', 'Actividad.idActividad', '=', 'PuntoEncuentro.idActividad');

        if($request->has('busqueda') && $request->busqueda == 'punto') {
            $query->join('atl_pais', 'PuntoEncuentro.idPais', '=', 'atl_pais.id')
                ->join('atl_provincias', 'PuntoEncuentro.idProvincia', '=', 'atl_provincias.id')
                ->join('atl_localidades', 'PuntoEncuentro.idLocalidad', '=', 'atl_localidades.id');
        } else { //por lugar de actividad
            $query->join('atl_pais', 'Actividad.idPais', '=', 'atl_pais.id')
                ->join('atl_provincias', 'Actividad.idProvincia', '=', 'atl_provincias.id')
                ->join('atl_localidades', 'Actividad.idLocalidad', '=', 'atl_localidades.id');
        }

            $query->where('Tipo.idCategoria', $categoria)
            ->orderBy('Tipo.nombre', 'asc')
            ->selectRaw('distinct Tipo.idTipo, Tipo.nombre');

        if ($request->has('localidades') && !is_null($request->localidades) && !empty($request->localidades)) {
            $query->whereIn('atl_localidades.id', array($request->localidades));
        }

        $tipos = $query->get();

        $listTipos = [];

        for ($i = 0; $i < count($tipos); $i++) {
            $idTipo = $tipos[$i]->idTipo;
            $listTipos[$i]['idTipo'] = $idTipo;
            $listTipos[$i]['nombre'] = $tipos[$i]->nombre;
        }
        return $listTipos;
    }


}

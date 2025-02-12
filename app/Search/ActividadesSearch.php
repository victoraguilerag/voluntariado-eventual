<?php

namespace App\Search;


use App\Actividad;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ActividadesSearch
{
    public static function apply(Request $filters)
    {
        $query = static::applyDecoratorsFromRequest($filters, ActividadesSearch::newQuery());
        return static::getResults($query);
    }
    private static function applyDecoratorsFromRequest(Request $request, Builder $query)
    {
        foreach ($request->all() as $filterName => $value) {
            $decorator = static::createFilterDecorator($filterName);
            if (static::isValidDecorator($decorator)) {
                $query = $decorator::apply($query, $value);
            }
        }
        return $query;
    }
    private static function createFilterDecorator($name)
    {
        return __NAMESPACE__ . '\\filters\\' . studly_case($name);
    }
    private static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }
    private static function getResults(Builder $query)
    {
        return $query->get();
    }

    private static function newQuery(){
        $query = (new Actividad)->newQuery();

        $query->join('Tipo', 'Actividad.idTipo', '=', 'Tipo.idTipo')
            ->leftJoin('PuntoEncuentro', 'Actividad.idActividad', '=', 'PuntoEncuentro.idActividad')
            ->selectRaw('distinct Actividad.*')
            ->orderBy('fechaInicio', 'desc')
            ->where('estadoConstruccion', 'Abierta')
            ->where('inscripcionInterna', 0) //Visibilidad pública
            ->where('fechaInicioInscripciones', '<=', date('Y-m-d H:i'))
            ->where('fechaFinInscripciones', '>=', date('Y-m-d H:i'))
            ->where('fechaInicio', '>=', date('Y-m-d H:i'));
        return $query;
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ajaxActividadesBusqueda extends TestCase
{
    private $actividades;

    use RefreshDatabase;

    public function setUp() 
    {
        parent::setUp();

        $this->actividades = factory(\App\Actividad::class,4)
            ->create()
            ->each(function ($a) {
                $a->puntosEncuentro()->save(factory(\App\PuntoEncuentro::class)->make());
            });
    }

    public function tearDown() 
    {
        parent::tearDown();
    }

    /**
     * @test
     *
     * Búsqueda por localidades en puntos de encuentro
     *
     * @return void
     */
    public function busquedaPorLocalidadPunto()
    {

        $params = [
        	'busqueda' => "punto",
			'localidades' => [$this->actividades[0]->puntosEncuentro()->first()->idLocalidad]
        ];

        $response = $this->post('/ajax/actividades', $params);

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /**
     * @test
     *
     * Búsqueda por localidades en lugar de actividad
     *
     * @return void
     */
    public function busquedaPorLocalidadLugar()
    {

        $params = [
            'busqueda' => "lugar",
            'localidades' => [$this->actividades[0]->idLocalidad]
        ];

        $response = $this->post('/ajax/actividades', $params);

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');

    }

    /**
     * @test
     *
     * Búsqueda por provincias en puntos de encuentro
     *
     * @return void
     */
    public function busquedaPorProvinciaPunto()
    {

        $params = [
            'busqueda' => "punto",
            'provincias' => [$this->actividades[0]->puntosEncuentro()->first()->idProvincia]
        ];

        $response = $this->post('/ajax/actividades', $params);

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /**
     * @test
     *
     * Búsqueda por provincias en lugar de la actividad
     *
     * @return void
     */
    public function busquedaPorProvinciaLugar()
    {

        $params = [
            'busqueda' => "lugar",
            'provincias' => [$this->actividades[0]->idProvincia]
        ];

        $response = $this->post('/ajax/actividades', $params);

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /**
     * @test
     *
     * Búsqueda por Tipo
     *
     * @return void
     */
    public function busquedaPorTipo()
    {

        $params = [
            'tipos' => [$this->actividades[0]->tipo()->first()->idTipo]
        ];

        $response = $this->post('/ajax/actividades', $params);

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /**
     * @test
     *
     * Búsqueda por categoria
     *
     * @return void
     */
    public function busquedaPorCategoria()
    {

        $params = [
            'categoria' => $this->actividades[0]->tipo()->first()->idCategoria
        ];

        $response = $this->post('/ajax/actividades', $params);

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /**
     * @test
     *
     * Búsqueda sin filtros
     *
     * @return void
     */
    public function busquedaFiltrosVacios()
    {
        //si se pone null en alguno, intenta la query con valor is null
        $params = [];

        /*$params = [
            'busqueda' => "punto" o "lugar",
            'localidades' => [],
            'provincias' => [],
            'tipos' => []
        ];}*/

        $response = $this->post('/ajax/actividades', $params);

        $response
            ->assertStatus(200)
            ->assertJsonCount(4, 'data');

    }

}
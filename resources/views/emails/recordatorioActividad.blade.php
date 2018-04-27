<html>
<head>
    <title>Recordatorio Actividad</title>
</head>
 
<body>
  <table border="0" style="border:1px solid #999999;font-family:Helvetica,sans-serif;font-size:12px" align="center">
    <tbody>
      <tr>
        <td>
          <p>Hola {{$inscripcion->persona->nombres}} {{$inscripcion->persona->apellidoPaterno}}: </p>
          <p> Te recordamos que te has inscrito para participar en <strong>{{$inscripcion->actividad->nombreActividad}}</strong> de TECHO - 
          @if($inscripcion->actividad->pais) 
            {{$inscripcion->actividad->pais->nombre}}
          @endif
            que inicia el {{$inscripcion->actividad->fechaInicio->format('d/m/Y')}}.  </p>
          <b>En:</b> {{$inscripcion->actividad->localidad->localidad}} 
          @if($inscripcion->actividad->provincia)
            {{$inscripcion->actividad->provincia->provincia}}
          @endif
          @if($inscripcion->actividad->coordinador)
            <b>Coordinador:</b> {{$inscripcion->actividad->coordinador->nombres}} {{$inscripcion->actividad->coordinador->apellidoPaterno}}
          @endif
          <p>{{$inscripcion->actividad->mensajeInscripcion}} <br></p>
          <p><b>Te esperamos!!!</b></p>
          @if($inscripcion->punto_encuento)
          <p>Punto de encuento</p>
          <ul>
            <li>
              <b>{{$inscripcion->punto_encuento->punto}}</b> - {{$inscripcion->punto_encuento->localidad->localidad}} - {{$inscripcion->punto_encuento->provincia->provincia}} - {{$inscripcion->punto_encuento->pais->nombre}} <b>horario:</b> {{$inscripcion->punto_encuento->horario}} <b>coordinador:</b> {{$inscripcion->punto_encuento->responsable->nombres}} {{$inscripcion->punto_encuento->responsable->apellidoPaterno}}
            </li>
          </ul>
          @endif
          <p> Para TECHO - Argentina es importante que te mantengas enterado de las nuevas actividades. Para ello, entra siempre en nuestro Sitio Web.  </p>
            <p> Muchas gracias!!  </p>
          </p>
        </td> 
      </tr>
      <tr>
        <td> 
          @include('emails.footer') 
        </td>
      </tr>
    </tbody>
  </table>
</body>
</html>
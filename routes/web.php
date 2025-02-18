<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\InscripcionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

// Route::get('/', 'HomeController@redirectAdmin')->name('index');

// Route::get('/evento/25', 'Frontend\EventoController@index')->name('evento.show');
// Route::post('/evento/inscripcion', 'Frontend\EventoController@storeParticipante')->name('evento.storeParticipante');









Route::post('/evento/registrar', 'Frontend\EventoController@storeParticipante')->name('evento.storeParticipante');
Route::post('/ofertas-academicas/registrar', 'Frontend\ProgramaController@storeParticipante')->name('programa.storeParticipante');
Route::get('/ofertas-academicas/inscribirse', 'Frontend\ProgramaController@viewInscribirse')->name('programa.inscribirse');
Route::post('/ofertas-academicas/inscribirse', 'Frontend\ProgramaController@postInscribirse')->name('programa.postInscribirse');
Route::get('/evento/inscribirse', 'Frontend\EventoController@viewInscribirse')->name('evento.inscribirse');
Route::post('/evento/inscribirse', 'Frontend\EventoController@postInscribirse')->name('evento.postInscribirse');

Route::post('/evento/asistencia', 'Frontend\EventoController@storeAsistencia')->name('evento.storeAsistencia');

Route::get('/logout-evento', 'Frontend\EventoController@logout')->name('evento.logout');
Route::get('/logout-programa', 'Frontend\ProgramaController@logout')->name('programa.logout');


Route::get('ofertas-academicas/comprobanteParticipante/{per_id}/{pro_id}','Frontend\ProgramaController@comprobanteParticipante')->name('programa.comprobanteParticipante');
Route::get('ofertas-academicas/comprobanteParticipantePdf/{per_id}/{pro_id}', 'Frontend\ProgramaController@comprobanteParticipantePdf')->name('programa.comprobanteParticipantePdf');
Route::get('ofertas-academicas/compromisoParticipantePdf/{per_id}/{pro_id}', 'Frontend\ProgramaController@compromisoParticipantePdf')->name('programa.compromisoParticipantePdf');
Route::get('ofertas-academicas/habilitacionParticipantePdf/{per_id}/{pro_id}', 'Frontend\ProgramaController@habilitacionParticipantePdf')->name('programa.habilitacionParticipantePdf');
Route::get('ofertas-academicas/rotuloParticipantePdf/{per_id}/{pro_id}', 'Frontend\ProgramaController@rotuloParticipantePdf')->name('programa.rotuloParticipantePdf');
Route::get('evento/comprobanteParticipante/{eve_per_id}/{eve_id}','Frontend\EventoController@comprobanteParticipante')->name('evento.comprobanteParticipante');
Route::get('evento/comprobanteParticipantePdf/{eve_per_id}/{eve_id}', 'Frontend\EventoController@comprobanteParticipantePdf')->name('evento.comprobanteParticipantePdf');

Route::get('/inicio', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index')->name('home');
// Route::get('/evento', 'HomeController@index')->name('home');

// Route::group(['prefix' => 'programa', 'controller' => 'Frontend\ProgramaController'], function () {
//     Route::get('/', 'index')->name('programa');
//     Route::get('/{pro_id}', 'show')->name('programa.show');
// });

Route::group(['prefix' => 'ofertas-academicas', 'controller' => 'Frontend\ProgramaController'], function () {
    Route::get('/', 'index')->name('programa');
    Route::get('/{pro_id}', 'show')->name('programa.show');
    Route::get('/inscripcion/{pro_id}', 'inscripcion')->name('programaInscripcion');

    // Rutas para AJAX
    Route::get('/get-sedes/{dep_id}/{pro_id}', 'getSedes')->name('programa.getSedes');
    Route::get('/get-turnos/{sede_id}/{pro_id}', 'getTurnos')->name('programa.getTurnos');
    Route::get('/solicitar-sede/{pro_id}', 'solicitarSedeInscripcion')->name('programa.solicitarSede');
    Route::post('/registrar-solicitud', 'solicitarSedePost')->name('programa.solicitarSedePost');
});
Route::group(['prefix' => 'profebotics', 'controller' => 'Frontend\BoticsController'], function () {
    Route::get('/', 'index')->name('profebotics');
    // Route::get('/{pro_id}', 'show')->name('programa.show');
});

Route::group(['prefix' => 'comunicado', 'controller' => 'Frontend\ComunicadoController'], function () {
    Route::get('/', 'index')->name('comunicado');
    Route::get('/{pro_id}', 'show')->name('comunicado.show');
});
Route::group(['prefix' => 'sedes', 'controller' => 'Frontend\SedeController'], function () {
    Route::get('/', 'index')->name('sede');
    Route::get('/{sede_id}', 'show')->name('sede.show');
});
Route::group(['prefix' => 'videos', 'controller' => 'Frontend\VideosController'], function () {
    Route::get('/', 'index')->name('videos');
});
Route::group(['prefix' => 'quienesSomos', 'controller' => 'Frontend\QuienesSomosController'], function () {
    Route::get('/', 'index')->name('quienesSomos');
});

Route::get('/reload-captcha', 'Frontend\EventoController@reloadCaptcha')->name('reload-captcha');

Route::group(['prefix' => 'evento', 'controller' => 'Frontend\EventoController'], function () {
    Route::get('/', 'eventos')->name('evento');
    Route::get('/detalle/{eve_id}', 'detalle')->name('eventoDetalle');
    Route::get('/inscripcion/{eve_id}', 'inscripcion')->name('eventoInscripcion');

    // ASISTENCIA EVENTO

    Route::get('/asistencia/{eve_id}', 'asistencia')->name('eventoAsistencia');
    Route::get('/{eve_id}', 'evento')->name('evento.show');
});


//VERIFICAR
Route::group(['prefix' => 'barcode', 'controller' => 'Backend\VerificarBarcodeController'], function () {
    Route::get('/certificado-nota/{barcode}', 'verificarCertificadoNota')->name('verificarCertificadoNota');
});


Route::get('/certificado-nota/{bar}', 'Backend\VerificarBarcodeController@verificarCertificadoNota')->name('verificarCertificadoNota');
Route::get('/registro-universitario/{bar}', 'Backend\VerificarBarcodeController@verificarRegistroUniversitario')->name('verificarRegistroUniversitario');


Route::group(['prefix' => 'novedades', 'controller' => 'Frontend\BlogController'], function () {
    Route::get('/', 'index')->name('blog');
    Route::get('/{blog_id}', 'show')->name('blog.show');
});
Route::group(['prefix' => 'galeria', 'controller' => 'Frontend\GaleriaController'], function () {
    Route::get('/', 'index')->name('galeria');
});






// Route::get('/evento/25', 'Frontend\EventoController@index')->name('evento.show');
// Route::get('/evento/26', 'Frontend\EventoController@show')->name('evento.show');
// Route::get('/evento/27', 'Frontend\EventoController@show')->name('evento.show');

/**
 * Admin routes
 */
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', 'Backend\DashboardController@index')->name('admin.dashboard');
    Route::resource('roles', 'Backend\RolesController', ['names' => 'admin.roles']);
    Route::resource('users', 'Backend\UsersController', ['names' => 'admin.users']);
    Route::resource('admins', 'Backend\AdminsController', ['names' => 'admin.admins']);




    Route::resource('profe', 'Backend\ProfeController', ['names' => 'admin.profe']);
    Route::resource('responsable', 'Backend\ResponsableController', ['names' => 'admin.responsable']);
    Route::resource('programa', 'Backend\ProgramaController', ['names' => 'admin.programa']);
    Route::resource('evento', 'Backend\EventoController', ['names' => 'admin.evento']);
    // Route::resource('evento-restriccion', 'Backend\EventoRestriccionController', ['names' => 'admin.eventorestriccion']);
    Route::get('evento-cuestionario/{eve_id}', 'Backend\EventoCuestionarioController@index')->name('admin.eventocuestionario.index');
    Route::get('evento-cuestionario/create/{eve_id}', 'Backend\EventoCuestionarioController@create')->name('admin.eventocuestionario.create');
    Route::post('evento-cuestionario', 'Backend\EventoCuestionarioController@store')->name('admin.eventocuestionario.store');
    Route::get('evento-cuestionario/{eve_id}/edit', 'Backend\EventoCuestionarioController@edit')->name('admin.eventocuestionario.edit');
    Route::put('evento-cuestionario/{eve_id}', 'Backend\EventoCuestionarioController@update')->name('admin.eventocuestionario.update');
    // Route::delete('evento-cuestionario/{eve_id}', 'Backend\EventoCuestionarioController@destroy')->name('admin.eventocuestionario.destroy');

    Route::get('evento-pregunta/{eve_id}', 'Backend\EventoPreguntaController@index')->name('admin.eventopregunta.index');
    Route::get('evento-pregunta/create/{eve_id}', 'Backend\EventoPreguntaController@create')->name('admin.eventopregunta.create');
    Route::post('evento-pregunta', 'Backend\EventoPreguntaController@store')->name('admin.eventopregunta.store');
    Route::get('evento-pregunta/{eve_id}/edit', 'Backend\EventoPreguntaController@edit')->name('admin.eventopregunta.edit');
    Route::put('evento-pregunta/{eve_id}', 'Backend\EventoPreguntaController@update')->name('admin.eventopregunta.update');
    Route::delete('evento-pregunta/{eve_id}', 'Backend\EventoPreguntaController@destroy')->name('admin.eventopregunta.destroy');


    Route::get('evento-opciones/create/{eve_id}', 'Backend\EventoPreguntaController@create')->name('admin.eventoopciones.create');
    Route::post('evento-opciones', 'Backend\EventoPreguntaController@storeOpciones')->name('admin.eventoopciones.store');
    Route::put('evento-opciones/{eve_opc_id}', 'Backend\EventoPreguntaController@updateOpciones')->name('admin.eventoopciones.updateopciones');
    Route::delete('evento-opciones/{eve_opc_id}', 'Backend\EventoPreguntaController@destroyopciones')->name('admin.eventoopciones.destroyopcion');


    Route::get('evento-cuestionario', 'Backend\InscripcionController@index')->name('admin.inscripcion.index');

    Route::resource('blog', 'Backend\BlogController', ['names' => 'admin.blog']);
    Route::resource('galeria', 'Backend\GaleriaController', ['names' => 'admin.galeria']);
    Route::resource('solicitudes', 'Backend\SolicitudesController', ['names' => 'admin.solicitudes']);
    Route::resource('comunicado', 'Backend\ComunicadoController', ['names' => 'admin.comunicado']);
    Route::resource('perfil', 'Backend\PerfilController', ['names' => 'admin.perfil']);

    // Estados
    Route::get('responsable/estado/{resp_profe_id}', 'Backend\ResponsableController@estado')->name('admin.responsable.estado');
    Route::get('programa/estado/{pro_id}', 'Backend\ProgramaController@estado')->name('admin.programa.estado');
    Route::get('evento/estado/{eve_id}', 'Backend\EventoController@estado')->name('admin.evento.estado');
    Route::get('blog/estado/{blog_id}', 'Backend\BlogController@estado')->name('admin.blog.estado');
    Route::get('galeria/estado/{galeria_id}', 'Backend\GaleriaController@estado')->name('admin.galeria.estado');
    Route::post('solicitudes/estado/{id}', 'Backend\SolicitudesController@estado')->name('admin.solicitudes.estado');

    Route::get('comunicado/estado/{comun_id}', 'Backend\ComunicadoController@estado')->name('admin.comunicado.estado');




    // Route::resource('calificacion', 'Backend\CalificacionController', ['names' => 'admin.calificacion']);
    Route::resource('ajedrez', 'Backend\AjedrezController', ['names' => 'admin.ajedrez']);
    Route::get('ajedrezIn/{sede_id}', 'Backend\AjedrezController@index')->name('admin.ajedrez.index');
    Route::post('ajedrez/{pi_id}', 'Backend\AjedrezController@storeAjedrez')->name('admin.ajedrez.storeajedrez');
    Route::resource('inscripcion', 'Backend\InscripcionController', ['names' => 'admin.inscripcion']);
    Route::get('calificacion/{sede_id}/{pro_id}', 'Backend\CalificacionController@index')->name('admin.calificacion.index');
    Route::get('calificacioninvestigacion/{sede_id}/{pro_id}', 'Backend\CalificacionController@investigacion')->name('admin.calificacion.investigacion');
    Route::get('calificacionx/{sede_id}/{pro_id}', 'Backend\CalificacionController@indexxx')->name('admin.calificacion.indexxx');
    Route::post('calificacion/{pi_id}/{pm_id}/{pc_id}', 'Backend\CalificacionController@storeCalificacion')->name('admin.calificacion.storecalificacion');

    //Route::post('inscripcion', [InscripcionController::class, 'store'])->name('admin.inscripcion.store');
    //Route::get('inscripcion/{id}/edit', [InscripcionController::class, 'edit'])->name('admin.inscripcion.edit');
    Route::put('residencia-nacimiento/{per_id}', 'Backend\InscripcionController@datosNacimientoResidencia')->name('admin.datosnacres.update');
    Route::get('calificacion-reporte/{sede_id}/{pro_id}/{pm_id}/{pro_tur_id}', 'Backend\CalificacionController@reporteCalificacionPdf')->name('admin.calificacion.reportecalificacionpdf');
    Route::get('calificacion-reporte-inv/{sede_id}/{pro_id}/{pm_id}/{pro_tur_id}', 'Backend\CalificacionController@reporteCalificacionInvPdf')->name('admin.calificacion.reportecalificacioninvpdf');
    // Route::get('calificacion-reporte/{sede_id}/{pro_id}/{pm_id}/{pro_tur_id}', 'Backend\CalificacionController@reporteInvCalificacionPdf')->name('admin.calificacion.reporteinvcalificacionpdf');
    // Route::get('certificado-ajedrez', 'Backend\InscripcionController@certificadoAjedrezPdf')->name('admin.inscripcion.certificadoajedrezpdf');
    Route::get('inscripcion-reporte/{sede_id}/{pro_id}', 'Backend\InscripcionController@reporteInscritoPdf')->name('admin.inscripcion.reporteinscritopdf');
    Route::get('inscripcionIn/{sede_id}', 'Backend\InscripcionController@index')->name('admin.inscripcion.index');
    Route::get('inscripcionI/{sede_id}', 'Backend\InscripcionController@create')->name('admin.inscripcion.create');
    Route::get('inscripcionbuscar', 'Backend\InscripcionController@buscadorPersona')->name('admin.inscripcion.buscadorpersona');
    Route::get('inscripcionbuscadorper', 'Backend\InscripcionController@buscadorPersona2')->name('admin.inscripcion.buscadorper');
    // Route::get('inscripcion/{sede_id}', 'Backend\InscripcionController@indexSede', ['names' => 'admin.inscripcion.indexsede']);
    Route::resource('sede', 'Backend\SedeController', ['names' => 'admin.sede']);

    // Login Routes
    Route::get('/login', 'Backend\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login/submit', 'Backend\Auth\LoginController@login')->name('admin.login.submit');

    // Logout Routes
    Route::post('/logout/submit', 'Backend\Auth\LoginController@logout')->name('admin.logout.submit');

    Route::post('/baucherpost/{id}', 'Backend\InscripcionController@baucherpost')->name('admin.inscripcion.baucherpost');
    Route::put('/baucherupdate/{pi_id}/{pro_bau_id}', 'Backend\InscripcionController@baucherupdate')->name('admin.inscripcion.baucherupdate');

    Route::get('/searchrda', 'Backend\AdminsController@searchRda')->name('admin.search.rda');
    Route::get('/search-rda', 'Backend\InscripcionController@searchRda')->name('admin.searchInscripcion.rda');
    Route::get('/get-turnos', 'Backend\InscripcionController@getTurnos')->name('admin.search.turnos');
    Route::get('/formularioPdf/{pi_id}', 'Backend\InscripcionController@formularioPdf')->name('admin.inscripcion.formulariopdf');
    Route::get('/participantepagoPdf/{pi_id}', 'Backend\InscripcionController@participantepagoPdf')->name('admin.inscripcion.participantepagopdf');






    //ACTA DE CONCLUSION DE DIPLOMADO
    Route::get('/acta-conclusion-pdf/{pi_id}', 'Backend\ActaConclusionController@gereararActaConclusionpdf')->name('admin.participante.actaconclusionpdf');
    Route::get('/certificado-notas-pdf/{pi_id}', 'Backend\ActaConclusionController@gereararCertificadoNotaspdf')->name('admin.participante.certificadonotanpdf');
    Route::get('/registro-universitario-pdf/{pi_id}', 'Backend\ActaConclusionController@gereararRegistroUniversitariopdf')->name('admin.participante.registrouniversitariopdf');


    Route::get('/participantes', 'Backend\ActaConclusionController@indexParticipantes')->name('admin.participantes.index');
    Route::post('/guardar-acta', 'Backend\ActaConclusionController@guardarActa')->name('admin.guardar.acta');
    Route::post('/certificacion-diplomado', 'Backend\ActaConclusionController@guardarCertificado')->name('admin.guardar.certificado');
    Route::get('/buscar-participante', 'Backend\ActaConclusionController@participanteEncontrado')->name('admin.buscar.participante');
    Route::get('/persona-datos', 'Backend\ActaConclusionController@index')->name('admin.acta.index');
    Route::get('/filtrar-inscripciones', 'Backend\ActaConclusionController@filtrarInscripciones')->name('admin.filtrar.inscripcion');
    Route::get('/obtener-datelles/{id}', 'Backend\ActaConclusionController@obtenerDetallesPersona')->name('admin.obtener.detalles');

});


Route::group(['prefix' => 'configuracion'], function () {
    Route::resource('programa', 'Configuracion\ProgramaController', ['names' => 'configuracion.programa']);
    Route::resource('restriccion', 'Configuracion\RestriccionController', ['names' => 'configuracion.restriccion']);

    Route::post('storeversion', 'Configuracion\ProgramaController@storeVersion')->name('configuracion.programa.storeversion');
    Route::post('updateversion/{pv_id}', 'Configuracion\ProgramaController@updateVersion')->name('configuracion.programa.updateversion');
    Route::delete('deleteversion/{pv_id}', 'Configuracion\ProgramaController@destroyVersion')->name('configuracion.programa.deleteversion');

    Route::post('storetipo', 'Configuracion\ProgramaController@storeTipo')->name('configuracion.programa.storetipo');
    Route::post('storeturno', 'Configuracion\ProgramaController@storeTurno')->name('configuracion.programa.storeturno');
    Route::post('storeduracion', 'Configuracion\ProgramaController@storeDuracion')->name('configuracion.programa.storeduracion');
    Route::post('storemodalidad', 'Configuracion\ProgramaController@storeModalidad')->name('configuracion.programa.storemodalidad');

    Route::resource('sede', 'Configuracion\SedeController', ['names' => 'configuracion.sede']);

    Route::resource('evento', 'Configuracion\EventoController', ['names' => 'configuracion.evento']);
});


Route::group(['prefix' => 'migration'], function () {
    Route::post('areatrabajo', 'Migration\OtrosController@areaTrabajo')->name('migration.otros.areatrabajo');
    Route::post('nivel', 'Migration\OtrosController@nivel')->name('migration.otros.nivel');
    Route::post('categoria', 'Migration\OtrosController@categoria')->name('migration.otros.categoria');
    Route::post('subsistema', 'Migration\OtrosController@subsistema')->name('migration.otros.subsistema');

    // MIGRACIONES--------------------------------------------------------------------------------------------------------------------------
    Route::resource('usuarios', 'Migration\UsuariosController', ['names' => 'migration.usuarios']);
    Route::resource('inscripciones', 'Migration\ProgramaInscripcionController', ['names' => 'migration.inscripciones']);
    Route::resource('otros', 'Migration\OtrosController', ['names' => 'migration.otros']);
    Route::resource('cargo', 'Migration\CargoController', ['names' => 'migration.cargo']);
    Route::resource('especialidad', 'Migration\EspecialidadController', ['names' => 'migration.especialidad']);
    Route::resource('departamento', 'Migration\DepartamentoController', ['names' => 'migration.departamento']);
    Route::resource('distrito', 'Migration\DistritoController', ['names' => 'migration.distrito']);
    Route::resource('unidadeducativa', 'Migration\UnidadEducativaController', ['names' => 'migration.unidadeducativa']);
});

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Sede;
use App\Models\Municipio;
use App\Models\CondicionEstudiante;
use App\Models\Perfil;
use App\Models\PersonaForanea;
use App\Models\Direccion;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{
    //la función info retorna la vista de los estudiantes en tablas
    public function info(Request $request)
    {
        // Define las columnas que siempre quieres seleccionar
        $columnas_seleccionadas = [
            'id_persona',
            'nombre_persona',
            'segundo_nombre_persona', 
            'apellido_persona',
            'segundo_apellido_persona', 
            'cedula_persona',
            'telefono_persona',
            'email_persona'
        ];

        // Verificar si se ha enviado una búsqueda
        if ($request->has('search')) {
            $search = $request->input('search');
            // Filtrar los estudiantes por nombre, apellido o cédula
            $estudiantes = Persona::query()
                ->where('nombre_persona', 'LIKE', "%{$search}%")
                ->orWhere('apellido_persona', 'LIKE', "%{$search}%")
                ->orWhere('cedula_persona', 'LIKE', "%{$search}%")
                ->select($columnas_seleccionadas) // Usamos las columnas definidas
                ->paginate(10);
        } else {
            // Si no hay búsqueda, obtener todos los estudiantes
            $estudiantes = Persona::query()
                ->select($columnas_seleccionadas) // Usamos las columnas definidas
                ->paginate(10);
        }
        // Retornar la vista con los estudiantes
        return view('dashboard.maestro.estudiantes', compact('estudiantes'));
    }

    //la función borrarEstudiante recibe una cédula y elimina el estudiante correspondiente
   public function deleteEstudiante($cedula)
    {   
        // Validar la cédula
        if(!is_numeric($cedula)){
            return abort(404, 'Cédula inválida. Debe ser un número.');
        }

        try{    
            //Recuperar el estudiante por su cédula
            $estudiante = Persona::where('cedula_persona', $cedula)->first();
            //Si no se encuentra el estudiante, retornar un error
            if(!$estudiante){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado.'
                ], 404);
            }
            //Eliminar el estudiante
            $estudiante
            ->where('cedula_persona', $cedula)
            ->delete();
            
            //Retornar una respuesta exitosa
            return response()->json([
                'status' => 'success',
                'message' => 'Estudiante eliminado correctamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar el estudiante: ' . $e->getMessage()
            ], 500);
        }
    }

    //La funcion showDetail recibe una cédula y retorna la datos detallados del estudiante
    public function detalleEstudiante($cedula)
    {
        if(!is_numeric($cedula)){
            return abort(404, 'Cédula inválida. Debe ser un número.');
        }
        // Buscar el estudiante por su cédula
        try{
            // Aquí también deberías cargar el segundo nombre y apellido si los vas a retornar
            $estudiante = Persona::where('cedula_persona', $cedula)->with("personaPnfs.pnf")->first();
            // Si no se encuentra el estudiante, retornar un error
            if (!$estudiante) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Estudiante no encontrado.'
                ], 404);
            }
            // Retornar los detalles del estudiante
            $response = [
                'cedula' => $estudiante->cedula_persona,
                'nombre' => $estudiante->nombre_persona,
                'segundo_nombre' => $estudiante->segundo_nombre_persona, 
                'apellido' => $estudiante->apellido_persona,
                'segundo_apellido' => $estudiante->segundo_apellido_persona, 
                'telefono'=> $estudiante->telefono_persona,
                'genero' => $estudiante->genero_persona,
                'fecha_nacimiento' => $estudiante->fecha_nacimiento_persona,
                'patria'=> $estudiante->regis_patria,
                'email'=> $estudiante->email_persona,
                'edad'=> $estudiante->edad_persona,
                'pnf' => isset($estudiante->personaPnfs[0]) ? $estudiante->personaPnfs[0]->pnf->nombre_pnf : null // Mejor manejo si no hay PNF
            ];

            return response()->json([
                'status' => 'success',
                'data' => $response
            ], 200);


        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al buscar el estudiante: ' . $e->getMessage()
            ], 500);
        }
    }


    public function serachEstudent(){
        // Esta función está vacía, quizás la uses más tarde
    }

    public function crearEstudiante(){

        $sedes = Sede::all();
        $municipios = Municipio::all();
        $condiciones = CondicionEstudiante::all();
        return view('dashboard.maestro.estudiante', compact('sedes', 'municipios', 'condiciones'));
    }

    public function store(Request $request){
        $reglas = [
            'cedula' => 'required|string|max:10|unique:persona,cedula',
            'nombre' => 'required|string|max:100',
            'segundo_nombre' => 'required|string|max:100', 
            'apellido' => 'required|string|max:100',
            'segundo_apellido' => 'required|string|max:100', 
            'sexo' => 'required|string|in:Masculino,Femenino,Otro',
            'telefono' => 'required|string|max:15',
            'fecha_nacimiento' => 'required|date',
            'edad' => 'required|integer|min:10|max:120',
            'email' => 'required|email|max:100',
            'regis_patria' => 'boolean',
            'es_foraneo' => 'boolean',

            //campo para la condicion estudiante

        ];
        // Aquí falta la lógica para validar y guardar los datos
        
    }

}
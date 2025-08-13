<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class EmpleadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    // consultar informacion de la base de datos
    {
        $datos['empleados']=Empleado::paginate(5);
        return view ('empleado.index', $datos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view ('empleado.create');
    }

    /**
     *
     */
    public function store(Request $request)
    // recive toda la informacion que se envia del formularioa travez del metodo "post"
    // de create para que se guarde diractamente en la tablas
    {
        // return view ('empleado.store');
        // $datosEmpleado=request()->all();
        $datosEmpleado=request()->except('_token');

        if($request->hasFile('Foto')){
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
        };

        Empleado::insert($datosEmpleado);

        return redirect('/empleado')->with('mensaje', 'Empleado creado exitosamente');

    }

    /**
     * Display the specified resource.
     */
    public function show(Empleado $empleado)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $empleado = Empleado::findOrFail($id);
        return view ('empleado.edit', compact('empleado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)

    // aqui se actualizan a la hora de guardar los datos en el edit
    {
        // busca el id, verifica si es igual al id recibido y lo actualiza en $datosEmpleado
        $datosEmpleado=request()->except(['_token','_method']);

        if($request->hasFile('Foto')){
            $empleado = Empleado::findOrFail($id);
            Storage::delete('public/'.$empleado->Foto);
            $datosEmpleado['Foto']=$request->file('Foto')->store('uploads','public');
        };


        Empleado::where('id','=', $id)->update($datosEmpleado);
// una vez actualizado nos envia de nuevo a la vista pero ya con los datos actualizados
        $empleado = Empleado::findOrFail($id);
        return redirect('/empleado')->with('mensaje', 'Empleado actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    // elliminar o borrar el archivo de empleado
    {
        $empleado = Empleado::findOrFail($id);

if ($empleado->Foto && Storage::disk('public')->exists($empleado->Foto)) {
    Storage::disk('public')->delete($empleado->Foto);
}
Empleado::destroy($id);


        return redirect('empleado');
    }
}

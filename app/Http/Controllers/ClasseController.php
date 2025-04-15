<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClasseStoreRequest;
use App\Http\Resources\ClasseResource;
use App\Models\Classe;
use Exception;
use Illuminate\Http\Request;

class ClasseController extends Controller
{

   public function index()
   {

       return ClasseResource::collection(Classe::with('classeType', 'students.records.exams','students.attendance')->get()) ;
   }

   /**
    * Store a newly created resource in storage.
    */
   public function store(ClasseStoreRequest $request)
   {
       try{
           $classes= $request->validated();
           $Classe = Classe::create($classes) ;
           $response = new ClasseResource($Classe) ;
           return response()->json([
               'Classe' => $response,
               'message' => __('Classe created successfully')
             ]);
       }catch(Exception $e){
           return response()->json([
               'message' => 'Failed to create Classe',
            'errors' => [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),],
                ], 500);
       }
   }

   /**
    * Display the specified resource.
    */
   public function show(Classe $classe)
   {
       //
   }

   /**
    * Update the specified resource in storage.
    */
   public function update(classestoreRequest $request, Classe $classe)
   {
       try{
           $newclasse= $request->validated();
            $classe->update($newclasse) ;
            ;
           return response()->json([
               'Classe' => $classe,
               'message' => __('Classe Updated successfully')
             ]);
       }catch(Exception $e){
           return response()->json([
               'message' => 'Failed to Update Classe',
            'errors' => [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),],
                ], 500);
       }
   }

   /**
    * Remove the specified resource from storage.
    */
   public function destroy($id)
   {
    $classe =Classe::find($id);
       try{
           $classe->delete();

          return response()->json([
           'message' => 'Classe successfully deleted!',
           'data' => $classe ,
           'status' =>201
           ], 201);

       }catch (\Exception $e) {
           return response()->json([
              'message' => 'Failed to delete Classe',
               ], 500);
           }
       }
}

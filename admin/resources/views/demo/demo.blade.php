

@extends('layouts.principal')

@section('titulo')Perfil usuario @endsection


@section('losCSS')@endsection


@section('content')

<h1>Prueba 1</h1>

<div class=" grid grid-cols-12 gap-2 " >

  <div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-start-2 lg:col-span-10 ">
    <div class="p-4 bg-gray-500">
      <div class=" grid grid-cols-12 gap-2 ">
        <div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 ">
          <div class="p-4 bg-gray-300">COL01</div>
        </div>
        <div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 ">
          <div class="p-4 bg-gray-300">COL02</div>
        </div>
      </div>
    </div>
  </div>

</div>

<p>################################</p>

<div class=" grid grid-cols-12 gap-2 " >

  <div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-start-2 lg:col-span-10 ">
    <div class="intro-y box">
      <div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200" >
      <h3 class="font-medium text-base mr-auto" >Titulos</h3>
    </div>
    <div class="p-5">
      <div class="preview">
        <div class=" grid grid-cols-12 gap-2 ">
          <div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 ">
            <!-- COL01 -->
            <div class="xl:w-1/2 sm:w-1/1" >
              <label for="">Nombre</label>
              <input type="text" class="input w-full border mt-2" placeholder="Input text">
            </div>
            <!-- FORM-GROUP -->
          </div>
          <div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 ">
            <!-- COL01 -->
          </div>
        </div>
            
      </div>
    </div>
        
    </div>
  </div>

</div>


<form method="POST" action="1" autocomplete="off" id="frmDocumento" >
  @csrf
</form>


@endsection


@section('scripts')@endsection




@extends('layouts.principal')

@section('titulo')Perfil usuario @endsection


@section('losCSS')

<link href="{{ asset('plugins/jquery-upload-file-master/css/uploadfile.css') }}" rel="stylesheet" />


<link href="{{ asset('plugins/Remodal-1.1.1/dist/remodal.css') }}" rel="stylesheet" />
<link href="{{ asset('plugins/Remodal-1.1.1/dist/remodal-default-theme.css') }}" rel="stylesheet" />





<style>
	.modal {
		transition: opacity 0.25s ease;
	}
	body.modal-active {
		overflow-x: hidden;
		overflow-y: visible !important;
	}
</style>


@endsection

@section('content')


<div class=" grid grid-cols-12 gap-2 " >

  <div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-start-2 lg:col-span-10 ">
    <div class="intro-y box">
    	<div class="flex flex-col sm:flex-row items-center p-5 border-b border-gray-200" >
			<h3 class=" font-medium text-base mr-auto " >Editar perfil usuario</h3>
		</div>
		<div class="p-5">
			<form method="POST" action="1" autocomplete="off" id="frmDocumento" >
				<input type="hidden" name="avatar_40" id="avatar_40" value="{{ Auth::user()->avatar_40 }}" />
				@csrf
				<div class="preview">
					<div class=" grid grid-cols-12 gap-2 ">
						<div class=" text-center col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 ">
							<!-- COL01 -->
							<img id="avatar" class=" m-0 m-auto rounded-lg md:w-56 " src="@if (Auth::user()->avatar == "") {{ asset('assets/img/user-demo.jpg') }} @else {{ Auth::user()->avatar }} @endif" alt="" />
							<div class=" w-40 mx-auto cursor-pointer relative mt-5 " >
								<a id="btnChngFoto" href="#" data-remodal-target="mdlImagen" class="button w-full bg-theme-1 text-white">Cambiar foto</a>
							</div>

						</div>
						<div class=" col-span-12 sm:col-span-12 md:col-span-12 lg:col-span-6 ">
							<!-- COL01 -->
							<div class=" mt-3 " >
								<label for="">Nombre</label>
								<input name="name" id="name" type="text" class=" mt-2 input w-full border mt-2" placeholder="Input text" maxlength="150" value="{{ Auth::user()->name }}" />
							</div>
							<!-- FORM-GROUP -->
							<div class=" mt-3 " >
								<label for="">Correo</label>
								<input name="email" id="email" type="text" class=" mt-2 input w-full border mt-2" placeholder="Input text" maxlength="150" value="{{ Auth::user()->email }}" />
							</div>
							<!-- FORM-GROUP -->
				            <div class=" mt-3 xl:w-1/2 sm:w-1/1" >
				              <label for="">Celuar</label>
				              <input id="celular" name="celular" type="text" class=" mt-2 input w-full border mt-2" placeholder="" value="{{ Auth::user()->celular }}" />
				            </div>
				            <!-- FORM-GROUP -->
						</div>
					</div>
							
				</div>
			</form>
		</div>
				
    </div>
  </div>

</div>






<div class="remodal" data-remodal-id="mdlImagen">
	<button data-remodal-action="close" class="remodal-close"></button>
	<h1 class=" font-medium text-base mr-auto " >Cargar archivos</h1>
	
	<div id="showoldupload"></div>

	<br>
	<button data-remodal-action="cancel" class=" float-left remodal-cancel"   >Cancelar</button>
	<button id="btnCambiar" data-remodal-action="confirm" class=" float-right remodal-confirm" >Cambiar</button>
</div>



@endsection


@section('scripts')

<script src="{{ asset('plugins/jquery-upload-file-master/js/jquery.uploadfile.min.js') }}" ></script>

<script src="{{ asset('plugins/Remodal-1.1.1/dist/remodal.min.js') }}" ></script>



<script>
	var uploadObj;
	var $avatarUser = '{{ Auth::user()->avatar }}', $avatar_40 = '{{ Auth::user()->avatar_40 }}';

(function($){
	$(document).ready(function()
		{
			/* ------------------------------------------------------------- */
			uploadObj = $("#showoldupload").uploadFile({
				url             :  _URL_HOME +  'adjuntar/foto/perfil/usuario' ,
				dragDrop        : true,
				fileName        : "formData",
				formData: {     '_token'  : $('meta[name="csrf-token"]').attr('content') , 'token' : _SessionToken } ,
				returnType      : "json",
				showDelete      : true,
				statusBarWidth  : 500,
				dragdropWidth   : 500,
				maxFileSize     : 20000*1024,
				showPreview     : true,
				previewHeight   : "70px",
				previewWidth    : "70px",
				dragDropStr     : "<span><b>Arrastra tus archivos aquí :)</b></span>",
				abortStr        : "Abandonar",
				cancelStr       : "Mejor no...",
				doneStr         : "Correcto",
				multiDragErrorStr : "Por favor revisa las restricciónes de archivos.",
				extErrorStr     : "Solo extensiónes de MS-Office e imágenes",
				sizeErrorStr    : "El máximo de tamaño es 20Mb:",
				uploadErrorStr  : "Error",
				uploadStr       : "Cargar",
				dynamicFormData: function()
				{
				    //var data ="XYZ=1&ABCD=2";
				    var data ={"XYZ":1,"ABCD":2};
				    return data;        
				},
				deleteCallback: function (data, pd) {
				    var $datita = { '_token'  : $('meta[name="csrf-token"]').attr('content') , 'id' : data.data.id , 'uu_id' : data.data.uu_id };
				    // console.log( 'Hola!!! >>>' , data , $datita );
				    if( data.data != undefined ){
				        $.post( "del/archivo/post" , $datita ,
				            function (resp,textStatus, jqXHR) {
				                //Show Message  
				                // alert("File Deleted");
				        });
				    }
				    pd.statusbar.hide(); //You choice.
				},
				afterUploadAll:function(files,data,xhr,pd)
				{
					console.log( files, data );
					var $n = files.responses.length;
					console.log( 'hay '+$n +' Archivos...');
					var $json = files.responses[$n-1];
					if( $json.estado == "OK" ){
						$avatarUser = $json.data.url_400;
						$avatar_40  = $json.data.url_40;
					    $('#btnCambiar').show();
					}
				}
			});
			/* ------------------------------------------------------------- */
			$('#btnChngFoto').click(function(event) {
				uploadObj.reset();
				$('#btnCambiar').hide();
			});
			/* ------------------------------------------------------------- */
			$('#btnCambiar').click(function(event) {
				event.preventDefault();
				cambiarFoto();
			});
			/* ------------------------------------------------------------- */
			/* ------------------------------------------------------------- */
			/* ------------------------------------------------------------- */
			/* ------------------------------------------------------------- */
			/* ------------------------------------------------------------- */
			/* ------------------------------------------------------------- */
			/* ------------------------------------------------------------- */
		});

})(jQuery);

/* ------------------------------------------------------------- */
function cambiarFoto()
{
	//
	var _data = { 
		'_token'  : $('meta[name="csrf-token"]').attr('content') , 
		'avatar' : $avatarUser, 'avatar_40' : $avatar_40
	};
	$('body').waitMe({
		effect : 'win8',text : 'Espere...',
		color:'#146436',fontSize:'20px',textPos : 'vertical',
		onClose : function() {}
	});
	$.post( _URL_HOME + 'update/avatar/usuario', _data , function(data, textStatus, xhr) {
		/*optional stuff to do after success */
	}, 'json')
	.fail(function() {
		//
	})
	.done( function( json ) {
		if( json.estado == 'OK' ){
			$('#frmDocumento #avatar').attr('src', $avatarUser );
		}
	})
	.always(function() {
		$('body').waitMe('hide');
	});
}
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */
/* ------------------------------------------------------------- */

</script>

@endsection


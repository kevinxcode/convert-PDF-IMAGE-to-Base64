<?php $priviledge = $this->session->userdata('priviledge_claim');  ?>
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						<!--begin::Entry-->
						<div class="d-flex flex-column-fluid">
							<!--begin::Container-->
							<div class="container">

								<!--begin::notif--

								<!--end::notif-->

								<!--begin::Card-->
								<div class="card card-custom">
									<div class="card-header flex-wrap py-2">
										<div class="card-title">
											<h3 class="card-label" >Travel Expense Claim Status
											</h3> |
									</div>
										<div class="card-toolbar">
											<!--begin::Button-->


											<!--end::Button-->

										</div>
									</div>
									<div class="card-body">
										<!--begin: Datatable-->
										<form action="<?php echo prefix_url;?>home/save"  onsubmit="save_sign()" enctype="multipart/form-data" method="POST">



										<div class="form-group">
										   <label for="pwd">Document Title </label>
										   <input type="text" name="id_title" id="id_title" autocomplete="off"  class="form-control">
										</div><br>




										<div class="form-group">
										   <label for="pwd">Document File *.pdf </label>
										   <input type="file" id="file_dat" onchange="onUpload(this.files)" autocomplete="off"  class="form-control"></input>
										</div><br>

										<div class="form-group">
										   <label for="pwd">Document Expired </label>
										  <select name="expired" id="expired" onchange="btn_expired()" class="form-control">
										    <option value="1">Lifetime</option>
										    <option value="0">with Expired date</option>
										  </select>
										</div><br>

										<div class="form-group" id="date_expired" style="display: none">
										    <label class="form-label">Date Expired : yyy-mm-dd</label>
										    <input type="text" name="exp_date" class="form-control" value="<?php echo date('Y-m-d'); ?>"  id="mdate">
										</div>

											<input type="hidden" name="id_b64" id="id_b64" autocomplete="off"  class="form-control">


										 <div id="pdf_img" ></div>

										 <img src="<?php echo prefix_url;?>assets/loading.gif" id="loader_img" width="28px" style="display: none">

										<!--end: Datatable-->
										    <button type="submit"  class="btn btn-primary">Create Sign</button>
									</form>
									</div>
								</div>
								<!--end::Card-->
							</div>
							<!--end::Container-->
						</div>
						<!--end::Entry-->
					</div>
					<!--end::Content-->


<!-- <script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@2.2.228/build/pdf.min.js"></script> -->
<script src="<?php echo prefix_url;?>assets/pdf_img/pdf.min.js"></script>

<script>
function view_img(cat){
	var img_src = $('#img_val_'+cat).val();
	var w = window.open("", "popupWindow", "width=600, height=400, scrollbars=yes");
	var $w = $(w.document.body).focus();
	w.focus();
	$w.html('<img src="' + img_src + '" width="100%" />');
}
</script>

<script>
function onUpload(files) {
		$('#img_arr_99').remove();
	 $('.pdf_append').remove();
  var file_data  = $("#file_dat").val();
  var ext = file_data.split(".");
  ext = ext[ext.length-1].toLowerCase();
  var arrayExtensions = ["pdf", "PDF","jpg","JPG","JPEG","jpeg","PNG","png"];
	// alert(ext);
  if (arrayExtensions.lastIndexOf(ext) == -1) {
      alert('Wrong extension type Allowed pdf, jpg, png');
      $("#file_dat").val("");
      $("#id_b64").val("");
      return false;
  }
	if(ext=='pdf'){
		$("#loader_img").show();
	   if (files.length !== 1) return;
	   const file = files[0];
	   let reader = new FileReader();
	   reader.onload = e => {
	     const data = atob(e.target.result.replace(/.*base64,/, ''));
	     renderPDF(data);
	   }
	   reader.readAsDataURL(file);
	}else{
		var file = files[0];
		var reader = new FileReader();
		reader.onloadend = function() {
			// console.log('RESULT', reader.result)
			var img_data = reader.result;
			  $("#pdf_img").append('<div id="img_arr_99"><input type="hidden" name="img_arr[]" id="img_val_99" class="form-control"   value="'+img_data+'"  ><span onclick="view_img(99)" style="margin: 8px" class="btn btn-primary btn-sm">VIEW (IMG)</span></div>');
		}
		reader.readAsDataURL(file);
	}

}

async function renderPDF(data) {
  const pdf = await pdfjsLib.getDocument({data}).promise;
	if(pdf.numPages>5){
		alert('a PDF file max 5 pages, please split to another item');
		$("#file_dat").val("");
		$("#id_b64").val("");
		return false;
	}
  for (let i = 1; i <= pdf.numPages; i++) {
    const image = document.createElement('img');
    document.body.appendChild(image);
    const page = await pdf.getPage(i);
    const viewport = page.getViewport({scale: 2});
    const canvas = document.createElement('canvas');
    const canvasContext = canvas.getContext('2d');
    canvas.height = viewport.height;
    canvas.width = viewport.width;
    await page.render({canvasContext, viewport}).promise;
    const data = canvas.toDataURL('image/png');
    // console.log(data);
    // var img_data = '<img src"'+data+'" width="100%">';
			$("#pdf_img").append('<div class="pdf_append"><input type="hidden" name="img_arr[]" id="img_val_'+i+'" class="form-control" value="'+data+'"  ><span onclick="view_img('+i+')" style="margin: 8px" class="btn btn-primary btn-sm"> VIEW PAGE ( '+i+' )</span></div>');
     $("#btn_submit").show();
		 $("#loader_img").show();
     $("#loader").hide();
    // $("#pdf_img").html(data);
    // image.src = data;
    // image.style.width = '100%';
    // console.log(data);
    // var img = document.getElementById('dishPhoto');
    // img.src = data;
    // img.style.width = '100%';
    // $("#id_b64").val(data);
    // document.getElementById("page_content").value = data;
  }
		 $("#loader_img").hide();

  // copy_img();
}


/*

*/
</script>

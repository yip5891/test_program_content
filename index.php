<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>Programming Test</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	<style type="text/css">
		.text-danger, 
		.help-block-error{
			color: red;
		}
		.text-success{
			color: green;
		}
		.has-error{
			border: 1px solid red;
		}

		.output-container{
			margin-top: 30px;
		}
	</style>
</head>
<body>
	<form id="formSubmit" method="post" enctype="multipart/form-data" class="form-horizontal" action="#">
		<div>
			<label for="txtNumPeople">Enter number of people:</label>
            <div class="form-group">
                <input type="text" id="txtNumPeople" name="txtNumPeople" onkeypress="return (event.charCode >= 48 && event.charCode <= 57)">
            </div>
		</div>
		<div>
			<input type="button" id="btnSubmit" name="btnSubmit" value="Submit" style="margin-top: 15px;">
		</div>
	</form>

	<div style="margin: 15px 0;">
		<div>Note:</div>
		<div>Maximum Card given: 13</div>
		<div>Minimum Card given: 0</div>
	</div>

	<div class="output-container">
		<div>Output:</div>
		<div id="output"></div>
	</div>
	<script type="text/javascript" src="js/jquery.min.js"></script> 
	<script type="text/javascript" src="js/jquery.validate.min.js"></script> 
	<script type="text/javascript">

		// button click 
		$("#btnSubmit").on("click", function(){
			$('#output').html("");
			let result = validateSubmitForm(); // validate form
			if (result) {
				// validate success 
				let formData = new FormData($("#formSubmit")[0]); 
				$.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "given_card.php",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        if (data.return_status == 0) {
                            alert("Failed given card");
                        } else {
                        	let html = "";
                           	let player = data.data;
                           	$.each(player, function(index, value) {
                           		let strCards = "";
                           		$.each(value.cards, function(index2, value2) {                    
								  	if (strCards.length > 0) {
								  		strCards = strCards + ',';
								  	}
								  	strCards = strCards + value2;
								});

							  	let output = "<div style='margin-bottom: 5px;'><div>Player "+ (index + 1) +"</div><div>"+ strCards +"</div></div>";
							  	html = html + output;
							});
							$("#output").html(html);
                        }
                    },
                    beforeSend:function(){
                    	$("#btnSubmit").prop("disabled", true);
                    },
                    complete: function(){
						$("#btnSubmit").prop("disabled", false);
	                }
                });
			} 
		});

		function validateSubmitForm(){
			let form = $("#formSubmit"); 
			let error = $('.text-danger', form);
            let success = $('.text-success', form);
            form.validate({
                errorElement: 'div', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "",  // validate all fields including form hidden input
                rules: {
                    "txtNumPeople": {
                        required: true,
                        number: true,
                        min:1,
                    },
                },
                errorPlacement: function (error, element) { // render error placement for each input type 
                    element.next().remove();
                    error.insertAfter(element); // for other inputs, just perform default behavior
                },
                invalidHandler: function (event, validator) { //display error alert on form submit              
                    success.hide();
                    error.show();
                },
                highlight: function (element) { // hightlight error inputs
                    $(element).addClass('has-error'); // set error class to the control group  
                },
                unhighlight: function (element) { // revert the change done by hightlight
                    $(element).removeClass('has-error'); // set error class to the control group 
                },
                success: function (label) {
                    label.removeClass('has-error'); // set success class to the control group
                },
                submitHandler: function (form) {
                    success.show();
                    error.hide(); 
                }
            });
            if (form.valid()) {
                return true;
            } else {
                return false;
            }
		}

	</script>
</body>
</html>
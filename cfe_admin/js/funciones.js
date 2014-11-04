jQuery(document).ready(function($) {
	$('#email').focus(); // Focus to the username field on body loads
	$('#email, #password').on('focus', function() {
		$('.login_result').remove();
	});
	$('#login').on('click', function(){ // Create `click` event function for login
		var email = $('#email'); // Get the username field
		var password = $('#password'); // Get the password field
		var login_result = $('.login_result');
		if(email.val() == ''){ // Check the username values is empty or not
			email.focus(); // focus to the filed
		}
		if(password.val() == ''){ // Check the password values is empty or not
			password.focus();
		}
		if(email.val() != '' && password.val() != ''){ // Check the username and password values is not empty and make the ajax request
			var e = email.val();
			var p = password.val();
			$.ajax({ // Send the credential values to another checker.php using Ajax in POST menthod
			url  : './checker.php',
			type : 'POST',
			data : { action:'login', email:e, pass:p},
			success: function(responseText){ // Get the result and asign to each cases
				if(responseText == 0){
					login_result.html('<span class="error">Email o Contraseña incorrectos!</span>');
				}
				else if(responseText == 1){
					window.open('dashboard.php','_self');
				}
				else{
					alert('Surgio un problema en el Servidor, intente de nuevo.');
				}
			}
			});
		}

		return false;
	});

	$('#container1').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
        	text: null
        },
        legend: {
        	enabled: false
        },
        xAxis: {
        	gridLineColor: 'rgba(51, 51, 51, 0.08)',
        	title: {
        		text: null
        	},
        	labels:{
        		enabled: false
        	}
        },
        yAxis: {
        	gridLineColor: 'rgba(51, 51, 51, 0.08)',
        	title: {
        		text: null
        	},
            labels:{
        		enabled: false
        	}
        },
        series: [{ 
        		data: [{name: 'Fallas',color: '#343434',y: 100}, {name: 'Quejas',color: '#009045',y: 31}]
        }],
        credits: {
        	enabled: false
        }
    });

	$('#container2').highcharts({
        chart: {
            type: 'column'
        },
        title: {
        	text: null
        },
        xAxis: {
        	title: {
        		text: null
        	},
            labels:{
        		enabled: false
        	}
        },
        yAxis: {
            min: 0,
            stackLabels: {
                enabled: true,
                style: {
                    fontWeight: 'bold',
                    color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                }
            },
            title: {
        		text: null
        	},
            labels:{
        		enabled: false
        	}
        },
        legend: {
            enabled: false
        },
       /* tooltip: {
            formatter: function () {
                return '<b>' + this.x + '</b><br/>' +
                    this.series.name + ': ' + this.y + '<br/>' +
                    'Total: ' + this.point.stackTotal;
            }
        },*/
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                    style: {
                        textShadow: '0 0 3px black, 0 0 3px black'
                    }
                }
            }
        },
        series: [{
            name: 'John',
            data: [5, 3, 4]
        }, {
            name: 'Jane',
            data: [2, 2, 3]
        }, {
            name: 'Joe',
            data: [3, 4, 4]
        }],
        credits: {
        	enabled: false
        }
    });

    
      
});
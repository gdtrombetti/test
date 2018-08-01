function init () {
  $.get({ url: 'Router.php',
    data: {method: 'getTeams'},
    dataType: 'json',
    success: function(output) {
    if (output.response.length > 0) {
      window.teams = output.response;
      $.each(output.response, function (key, value) {
        $('.cool-body').append(
          '<tr id="'+ value.ID +'" class="selectable">' +
          '<td>'+ (key + 1) + '</td>' +
          '<td>'+ value.NAME + '</td>' +
          '<td>'+ value.CITY + '</td>' +
          '<td style="background-color:' + value.COLOR + '"></td></tr>'
         );
        })
      }
    },
    error: function(error) {
      console.error(error);
    }
  });
  $.getJSON( 'config.json', function( data ) {
    $.get({url:'https://cors-anywhere.herokuapp.com/https://api.fortnitetracker.com/v1/profile/pc/poonsdegah',
      headers: {"TRN-Api-Key": data.FN.KEY},
      dataType: 'json',
      beforeSend:function(){
        $('#loader').show();
      },
      complete:function(){
        $('#loader').hide();
        $('#fortnite').show();
      },
      success: function(output) {
        if (output.epicUserHandle !== null) {
          $("#userName").html('User Name: ' + output.epicUserHandle);
        }
        if (output.lifeTimeStats.length > 0) {
          $.each(output.lifeTimeStats, function (key, value) {
            $('#life-stats').append(
              '<div id="fn_'+key+'"><p>'+ value.key + ':  ' + value.value + '</p></div>'
            );
          })
        }
        if (!$.isEmptyObject(output.stats) && !$.isEmptyObject(output.stats.p10)) {
          $.each(output.stats.p10, function (key, value) {
            $('#duo-stats').append(
              '<div id="fnd_'+key+'"><p>'+ value.label + ':  ' + value.value + '</p></div>'
            );
          })
        }
      }
    });
  });
}
function addStuff(output) {
  $('.cool-body').empty();
  $.each(output.response, function (key, value) {
    $('.cool-body').append(
      '<tr id="'+ value.ID +'" class="selectable">' +
      '<td>'+ (key + 1) + '</td>' +
      '<td>'+ value.NAME + '</td>' +
      '<td>'+ value.CITY + '</td>' +
      '<td style="background-color:' + value.COLOR + '"></td></tr>'
    );
  })
}
$(function() {
  $('#save-csv').click(function () {
    const file = document.getElementById('csv-file').files[0];
    if (file) {
      // create reader
      Papa.parse(file, {
        skipEmptyLines: true,
        complete: function(results) {
          $.post({url: 'Router.php',
            data: {method: 'addTeams', request: results.data},
            dataType: 'json',
            success: function(output) {
              if (output.response.length > 0) {
                window.teams = output.response;
                $('.success').show();
                $('.success').fadeOut(2000);
                addStuff(output);
              }
            },
            error: function(output) {
              $.each(output.responseJSON.response, function (key, value) {
                 $('.modal-body').append(value).css({color: 'red'});
              });
            }
          })
        }
      });
    }
  });
});

$(function() {
  function handleCSV() {
    var csv = Papa.unparse({
      fields: ["NAME", "CITY", "COLOR"]
    });
    // I did not come up with the solution below
    var csvData = new Blob([csv], {type: 'text/csv;charset=utf-8;'});
    //IE11 & Edge
    if (navigator.msSaveBlob) {
      navigator.msSaveBlob(csvData, 'export.csv');
    } else {
      //In FF link must be added to DOM to be clicked
      var link = document.createElement('a');
      link.href = window.URL.createObjectURL(csvData);
      link.setAttribute('download', 'export.csv');
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  }

  $('#download-csv').click(function () {
    handleCSV();
  });

  $('[data-toggle="tooltip"]').tooltip();
});

$(function() {
  $('#team-table').on('click', '.selectable', function() {
    window.idToDelete = $(this).attr('id');
    $('#deleteRow').show();
    $(this).addClass('active').siblings().removeClass('active');
  });
  $('#deleteRow').click(function () {
    $.ajax({
      url: 'Router.php',
      type: 'POST',
      data: {method: 'deleteTeam', id: window.idToDelete},
      dataType: 'json',
      success: function(output) {
        if (output) {
          $('#deleteRow').hide();
          $('.success-delete').show();
          $('.success-delete').fadeOut(2000);
          addStuff(output);
        }
      },
      error: function(output) {
        $.each(output.responseJSON.response, function (key, value) {
          //$('.modal-body').append(value).css({color: 'red'});
        });
      }
    });
  });
});
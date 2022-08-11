<!-- <!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
  <title>Google Places Autocomplete InputBox Example Without Showing Map - Tutsmake.com</title>
 <style>
    .container{
    padding: 10%;
    text-align: center;
   }
 </style>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-12"><h2>Google Places Autocomplete InputBox Example Without Showing Map</h2></div>
        <div class="col-12">
            <div id="custom-search-input">
                <div class="input-group">
                    <input id="autocomplete_search" name="autocomplete_search" type="text" class="form-control" placeholder="Search" />
                    <input type="hidden" name="lat">
                    <input type="hidden" name="long">
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCaTRVT5takPhaXK_jx9DKjygYEj11lUPY&libraries=places"></script>

<script>
  google.maps.event.addDomListener(window, 'load', initialize);
    function initialize() {
      var input = document.getElementById('autocomplete_search');
      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.addListener('place_changed', function () {
      var place = autocomplete.getPlace();
      // place variable will have all the information you are looking for.
      $('#lat').val(place.geometry['location'].lat());
      $('#long').val(place.geometry['location'].lng());
    });
  }
</script>
</body>
</html> -->
<?php
include "exportpdf.php";
include "includes/lateral.php";
$pageTitle = $mensaje[929];
include "includes/header.php";
include "ABM_MaestroPersonal_C.php";
include "includes/funciones.php";
?>

<!-- <html>
  <head>
    <title>Place Autocomplete Address Form</title>
    <link
      href="https://fonts.googleapis.com/css?family=Roboto:400,500"
      rel="stylesheet"
    />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCaTRVT5takPhaXK_jx9DKjygYEj11lUPY&libraries=places&callback=GoogleMapsDemo.Application.Init" async defer></script>

    <link rel="stylesheet" type="text/css" href="css/custom2.css" />
    <script type="text/javascript" src="js/auto-complete.js"></script>
  </head> -->
  <fieldset class="address">
    <legend>Address</legend>

    <div class="form-group">
        <label class="control-label col-sm-2 col-md-3">
            Address
        </label>
        <div class="col-sm-6 col-md-4">
            <input class="form-control places-autocomplete" type="text" id="Street" name="Street" placeholder="" value="" autocomplete="address-line1">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2 col-md-3">
            Apt/Suite #
        </label>
        <div class="col-sm-6 col-md-4">
            <input class="form-control" type="text" id="Street2" name="Street2" value="" autocomplete="address-line2">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2 col-md-3">
            Postal/Zip Code
        </label>
        <div class="col-sm-2 col-md-2">
            <input class="form-control places-autocomplete" type="text" id="PostalCode" name="PostalCode" placeholder="" value="" autocomplete="postal-code">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2 col-md-3">
            City
        </label>
        <div class="col-sm-4 col-md-3">
            <input class="form-control" type="text" id="City" name="City" value="" autocomplete="address-level2">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2 col-md-3">
            Country
        </label>
        <div class="col-sm-4 col-md-3">
            <input class="form-control" type="text" id="Country" name="Country" value="" autocomplete="country">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-2 col-md-3">
            State/Territory
        </label>
        <div class="col-sm-4 col-md-3">
            <input class="form-control" type="text" id="State" name="State" value="" autocomplete="address-level1">
        </div>
    </div>
</fieldset>
  </body>
</html>
<?php include "includes/footer.php" ?>

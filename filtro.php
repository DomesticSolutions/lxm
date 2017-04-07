<?php
$choice = null;
switch($_GET['e'])
{
  case 'g':
    $choice = 'Género';
    break;
  case 'v':
    $choice = 'Vandalismo';
    break;
  case 'c':
    $choice = 'CdL y Corrupción';
    break;
  case 'b':
    $choice = 'Violencia Escolar';
    break;
  case 'd':
    $choice = 'Derechos Humanos';
    break;
  case 'l':
    $choice = 'Legalidad por México';
    break;
  default:
    $choice = 'No hay información';
    break;
}

?>
<!DOCTYPE html>
<html>
<title>Legalidad por México</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Montserrat", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}

</style>
<body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script src="https://www.w3schools.com/lib/w3.js"></script>
<script>
  
  var objReg = {};
    $(document).ready(function(){
        //showInfo('Leonardo', 'Acosta', 'Ortega');
          getDirectory('<?php echo($choice); ?>', $('#id02'));

          $('#btnModify').click(function(a){
                var b = document.getElementsByTagName('form')[1];
                if(b.checkValidity())
                {
                  
                  alert($('#idModify').val());
                }
                a.preventDefault();

          });
          
          $('#btnSave').click(function(a){
            var sre = $('#l').val();
            var sre1 = sre.replace(/\"/g,'\'');
            var sre2 = sre1.replace('600','400px');
            var sre3 = sre2.replace('450','380px');
            
              var b = document.getElementsByTagName('form')[0];
                if(b.checkValidity())
                {
                  
                  objReg.L1 = $('#a').val();
                  objReg.L2 = $('#b').val();
                  objReg.L3 = $('#c').val();
                  objReg.L4 = $('#d').val();
                  objReg.L5 = $('#e').val();
                  objReg.L6 = $('#f').val();
                  objReg.L7 = $('#g').val();
                  objReg.L8 = $('#h').val();
                  objReg.L9 = $('#i').val();
                  objReg.L10 = $('#j').val();
                  objReg.L11 = '<?php echo($choice); ?>';
                  objReg.L12 = sre3;
                  
                  var json = JSON.stringify(objReg);
                  
                  $.post('../Business/bsnsDirectory.php',{iCase:1, strArg1: json}, function(f){
                    //$('#lblIns').val(f);
                      alert('OK, gracias ' + f);
                      document.getElementById('id01').style.display='none';
                      $('#a').val('');
                      $('#b').val('');
                      $('#c').val('');
                      $('#d').val('');
                      $('#e').val('');
                      $('#f').val('');
                      $('#g').val('');
                      $('#h').val('');
                      $('#i').val('');
                      $('#j').val('');
                      $('#k').val('');
                      $('#l').val('');
                      getDirectory('<?php echo($choice); ?>', $('#id02'));
                    });
                    a.preventDefault();
                }
            
            });
          
      
      });
    

    function getDirectory(category, domComponent)
    {
        domComponent.empty();
        $.post('../Business/bsnsDirectory.php',{iCase:2,strArg1:category},function(f){
          var items = [];
          items.push('<tr><th>OSC</th><th>Ubicación</th><th>Servicios</th><th>Atiende</th><th>Horario</th><th>Costos</th><th></th></tr>');
          $.each(jQuery.parseJSON(f), function() {
            //alert(this.L11);
            var sd = this.L11.replace(/\"/g,' ');
            //var sd = "<label>"+this.L11+"</label>";
            //var sd="<label style=color:#000fff>Leonardo ACOR<label>";

            //items.push("<tr class='item'><td><a href='"+this.L2+"'>" + this.L1 + "<a></td><td>" + this.L3 + "</td><td>" + this.L6 + "</td><td>" + this.L7 + "</td><td>" + this.L8 + "</td><td>" + this.L9 + "</td><td><button onclick='alert(\""+this.L9+"\")' class='w3-button w3-large w3-green' style='width:100%;'>ver más</button><br><button onclick='alert(\"modificar\");' class='w3-button w3-large w3-blue' style='width:100%'>modificar</button><br>");
            items.push("<tr class='item'><td><a href='"+this.L2+"'>" + this.L1 + "<a></td><td>" + this.L3 + "</td><td>" + this.L6 + "</td><td>" + this.L7 + "</td><td>" + this.L8 + "</td><td>" + this.L9 + "  </td><td><button onclick='document.getElementById(\"divModal\").style.display=\"block\"; showInfo(\""+this.L5+"\", \""+this.L4+"\", \"" + this.L10 + "\", \"" + this.L2 + "\", \""+sd+"\");' class='w3-button w3-large w3-green' style='width:100%;'>ver más</button><br><button onclick='document.getElementById(\"divModify\").style.display=\"block\";document.getElementById(\"idModify\").value = this.id;' id=\""+this.L0+"\" class='w3-button w3-large w3-blue' style='width:100%'>modificar</button><br><button onclick='alert(\"borrar\");' class='w3-button w3-large w3-red' style='width:100%'>borrar</button></td></tr>");
            //items.push('<tr class="item"><td><a href="'+this.L2+'">' + this.L1 + '<a></td><td>' + this.L3 + '</td><td>' + this.L6 + '</td><td>' + this.L7 + '</td><td>' + this.L8 + '</td><td>' + this.L9 + '</td><td><button onclick="document.getElementById(\'divModal\').style.display=\'block\'; showInfo(\''+this.L5+'\',\''+this.L4+'\', \''+this.L10+'\', \''+this.L2+'\', \''+sd+'\')" class="w3-button w3-large w3-green" style="width:100%;">ver más</button><br><button onclick="alert(\'modificar\');" class="w3-button w3-large w3-blue" style="width:100%">modificar</button><br><button onclick="alert(\'borrar\');" class="w3-button w3-large w3-red" style="width:100%">borrar</button></td></tr>');
          });	 
          domComponent.append( items.join('') );
          
        });
    }
    
function showInfo(mh, mc, mf, ms, mm)
{
  $('#divMH').text(mh);
  $('#divMC').text(mc);
  $('#divMF').text(mf);
  $('#divMS').text(ms);
  $('#divMM').html(mm);
  //$('#lblIns').text('Le padrine');
  //$('#lblIns1').text('Le padrine 1');
  
  //$('#lblIns1').text('<iframe src= https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.386756968628!2d-106.07946068537024!3d28.618168491482738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86ea5cbae1dd702b%3A0xde8f54a99e35439a!2sAv+20+de+Noviembre+4112%2C+Pac%C3%ADfico%2C+RUTA+SUR+II%2C+31020+Chihuahua%2C+Chih.!5e0!3m2!1sen!2smx!4v1491423619928" width="400px" height="380px" frameborder="0" style="border:0" allowfullscreen></iframe>');
  
  //$('#lblIns1').text('<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.386756968628!2d-106.07946068537024!3d28.618168491482738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86ea5cbae1dd702b%3A0xde8f54a99e35439a!2sAv+20+de+Noviembre+4112%2C+Pac%C3%ADfico%2C+RUTA+SUR+II%2C+31020+Chihuahua%2C+Chih.!5e0!3m2!1sen!2smx!4v1491423619928" width="400px" height="380px" frameborder="0" style="border:0" allowfullscreen></iframe>');
  //$('#lblIns1').text('<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7405.017976142651!2d-102.31456034616969!3d21.876467406459508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7a7f2817fd77662c!2sTeatro+Victor+Sandoval!5e0!3m2!1sen!2smx!4v1491428051437" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>');
  //$('#lblIns').text('<iframe src=\'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3502.386756968628!2d-106.07946068537024!3d28.618168491482738!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x86ea5cbae1dd702b%3A0xde8f54a99e35439a!2sAv+20+de+Noviembre+4112%2C+Pac%C3%ADfico%2C+RUTA+SUR+II%2C+31020+Chihuahua%2C+Chih.!5e0!3m2!1sen!2smx!4v1491423619928\' width=\'400px\' height=\'380px\' frameborder=\'0\' style=\'border:0\' allowfullscreen></iframe>');
  $('#aMS').attr('href',ms);
}
    

function myFunction() {
    var x = document.getElementById("navDemo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>
<!-- Navbar -->
<div id="he0" class="w3-top">
  <div id="navFull" class="w3-bar w3-indigo w3-card-2 w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-indigo" href="javascript:void(0);" onclick="myFunction();" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="directorio.php" class="w3-bar-item w3-button w3-padding-large w3-light-gray w3-hover-indigo">Inicio</a>
    <a href="filtro.php?e=g" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-light-gray">Género</a>
    <a href="filtro.php?e=v" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-light-gray">Vandalismo</a>
    <a href="filtro.php?e=c" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-light-gray">CdL y Corrupción</a>
    <a href="filtro.php?e=b" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-light-gray">Violencia Escolar</a>
    <a href="filtro.php?e=d" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-light-gray">Derechos Humanos</a>
    <!--<a href="filtro.php?e=l" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-light-gray">Legalidad por México</a>-->
  </div>

  <!-- Navbar on small screens -->
  <div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium w3-large">
    <a href="filtro.php?e=g" class="w3-bar-item w3-button w3-padding-large">Género</a>
    <a href="filtro.php?e=v" class="w3-bar-item w3-button w3-padding-large">Vandalismo</a>
    <a href="filtro.php?e=c" class="w3-bar-item w3-button w3-padding-large">CdL y Corrupción</a>
    <a href="filtro.php?e=b" class="w3-bar-item w3-button w3-padding-large">Violencia Escolar</a>
    <a href="filtro.php?e=d" class="w3-bar-item w3-button w3-padding-large">Derechos Humanos</a>
    <!--<a href="filtro.php?e=l" class="w3-bar-item w3-button w3-padding-large">Legalidad por México</a>-->
  </div>
</div>

<!-- Header -->
<!--<header class="w3-container w3-indigo w3-center"  style="padding:128px 16px">
  <br><br><br>
  <div class="w3-display-container w3-text-white">
    <div class="w3-display-left" >
        <div class="w3-card-4 w3-blue w3-padding-32">
          <div class="w3-container">
              <img alt="logo LxM" src="logo_b.png" class="w3-left" style="width: 80px; height: auto;" />
              <h2 class="w3-xlarge">DIRECTORIO DE INSTITUCIONES</h2>
          </div>
          <div class="w3-container w3-white w3-text-blue">
              <h1 class="w3-xxlarge"><?php echo($choice); ?></h1>
          </div>
        </div>
    </div>
    <div class="w3-display-right" >
      <p><button id="btnNew" class="w3-button w3-light-blue w3-hover-light-gray w3-text-white w3-padding-large w3-large w3-margin-top" style="width: auto;">Agregar institución</button></p>
    </div>
  </div>
</header>-->

<header class="w3-container w3-indigo w3-center"  style="padding:100px 16px">
    <div class="w3-row">
      <div class="w3-third w3-container">
      
      </div>
      <div class="w3-third w3-container">
        <div class="w3-card-4 w3-blue w3-padding-32">
          <div class="w3-container">
              <img alt="logo LxM" src="logo_b.png" class="w3-center" style="width: 45%; height: auto;" />
              <!--<i class="fa fa-bullseye w3-text-white w3-margin-right" style="font-size:60px;"></i>-->
              <h2 class="w3-xlarge">DIRECTORIO DE INSTITUCIONES</h2>
          </div>
          <div class="w3-container w3-white w3-text-blue">
              <h1 class="w3-xxlarge"><?php echo($choice); ?></h1>
          </div>
          <!--<button id="btnNew"  class="w3-button w3-teal w3-hover-light-gray w3-padding-large w3-large w3-margin-top w3-text-white w3-circle">+</button>-->
        </div>
      </div>
      
      <div class="w3-third w3-container ">
        <!--<button class="w3-button w3-teal w3-hover-light-gray w3-padding-large w3-large w3-margin-top w3-text-white w3-circle">+</button>-->
        <button onclick="document.getElementById('id01').style.display='block'" class="w3-button w3-light-blue w3-hover-light-gray w3-text-white w3-padding-large w3-large w3-margin-top" style="width: auto;" >Agregar institución</button>
      </div>
    </div>
  
</header>

<div id="he1" class="w3-row-padding w3-padding-32 w3-container">
  <h2>Buscador de instituciones <?php echo($choice) ?></h2>

    <p><input oninput="w3.filterHTML('#id02', '.item', this.value)" placeholder="¿Qué es lo que buscas?" class="w3-input w3-padding-16 w3-border" type="text"></p>
</div>

<div id="he2" class="w3-row-padding w3-light-grey w3-padding-32 w3-container">
  <div class="w3-responsive"><table id="id02" class="w3-table-all"></table></div>  
</div>
<div id="he3" class="w3-row-padding w3-padding-32 w3-container"></div>

<!-- Footer -->
<footer class="w3-container w3-padding-32 w3-center w3-indigo">
  <p><a href="https://legalidadpormexico.org" target="_blank">Legalidad por México, c. Hamburgo 14-PB, col. Juárez, del. Cuauhtémoc, 06600, CDMX</a></p>
</footer>

  <div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:80%">
  
      <div class="w3-center">
        <span onclick="document.getElementById('id01').style.display='none';" class="w3-button w3-xlarge w3-transparent w3-display-topright" title="Close Modal">×</span>
        <h2>Registro de institución</h2>
      </div>
      <form class="w3-container">
        <div class="w3-section">
          <label><b>OSC</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="ingresa el nombre de la organización" id="a" maxlength="200" required />
          <label><b>Página oficial</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="http://ejemplo.com" id="b" maxlength="200" required />
          <label><b>Ubicación</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="calle, num-ext, num-int, colonia, delegación/municipio, estado" id="c" maxlength="200" required />
          <label><b>Teléfono</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="01800 1234 5678, 5524068723, 0155 3456 9876" id="d" maxlength="200" required />
          
          <label><b>Correo electrónico</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="email" placeholder="contacto@ejemplo.com" id="e" maxlength="200" required />
          
          <!--<input class="w3-input w3-border w3-margin-bottom"   type="text" placeholder="servicios que ofrece" id="f" maxlength="500" required />-->
          <label for="f"><b>Servicios que ofrece</b></label>
          <textarea id="f" rows="5" cols="15" class="w3-input w3-border w3-margin-bottom" placeholder="escribe los servicios que ofrece la institución" maxlength="500" required></textarea>
          
          <label><b>Atiende</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="nombre completo" id="g" required maxlength="200" />
          <label><b>Horarios</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="l-v 8am-6:30pm, sábados 8am-3pm" id="h" maxlength="200" required />
          
          <label><b>Costos</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="costos de los servicios" id="i" maxlength="45" required />
          <label><b>Contacto</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="nombre completo" id="j" maxlength="200" required />
          
          <label><b>Legalidad por México</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="escribe tu correo" id="k" maxlength="45"  />
          
          
          <label><b>Ubicación mapa</b></label>
          <textarea rows="5" cols="15" class="w3-input w3-border w3-margin-bottom" placeholder="escribe el texto que aparece en google maps" id="l" maxlength="500"> </textarea>

          <button id="btnSave" class="w3-button w3-block w3-indigo w3-section w3-padding" type="button">Guardar</button>
        </div>
      </form>

    </div>
  </div>


<div id="divModal" class="w3-modal">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom">
    <div class="w3-container"> 
        <span onclick="document.getElementById('divModal').style.display='none';" class="w3-button w3-xlarge w3-display-topright">&times;</span>
        <header> Saber más </header>
        <div class="w3-row">
          <div class="w3-half w3-container w3-large ">
                <h3 class="w3-center">Información</h3>
                <hr>
                <i class="fa fa-envelope"></i>
                <label id="divMH"></label>
                <br>
                <i class="fa fa-globe"></i>
                <a id="aMS"><label id="divMS"></label></a>
                <hr>
                <i class="fa fa-phone"></i>
                <label id="divMC"></label>
                <hr>
                <i class="fa fa-user"></i>
                <label id="divMF"></label>
                <hr>

          </div>
          <div class="w3-half w3-container w3-center">
                <h3>Ubicación</h3>
                <hr>
                <div id="divMM"></div>
                <hr>
          </div>
        </div>
        <footer class="w3-center">Legalidad por México - Directorio de Instituciones</footer>
        </div>
        
    </div>
  </div>


<div id="divModify" class="w3-modal">
  <div class="w3-modal-content w3-card-4 w3-animate-zoom">
    <div class="w3-container"> 
        <span onclick="document.getElementById('divModify').style.display='none';" class="w3-button w3-xlarge w3-display-topright">&times;</span>
        <header> modificar </header>
        <section>
        <form class="w3-container">
        <div class="w3-section">
        <label id="idModify"></label>
          <label><b>OSC</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="ingresa el nombre de la organización" id="a1" maxlength="200" required />
          <label><b>Página oficial</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="http://ejemplo.com" id="b1" maxlength="200" required />
          <label><b>Ubicación</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="calle, num-ext, num-int, colonia, delegación/municipio, estado" id="c1" maxlength="200" required />
          <label><b>Teléfono</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="01800 1234 5678, 5524068723, 0155 3456 9876" id="d1" maxlength="200" required />
          
          <label><b>Correo electrónico</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="email" placeholder="contacto@ejemplo.com" id="e1" maxlength="200" required />
          
          <!--<input class="w3-input w3-border w3-margin-bottom"   type="text" placeholder="servicios que ofrece" id="f" maxlength="500" required />-->
          <label for="f1"><b>Servicios que ofrece</b></label>
          <textarea id="f1" rows="5" cols="15" class="w3-input w3-border w3-margin-bottom" placeholder="escribe los servicios que ofrece la institución" maxlength="500" required></textarea>
          
          <label><b>Atiende</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="nombre completo" id="g1" required maxlength="200" />
          <label><b>Horarios</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="l-v 8am-6:30pm, sábados 8am-3pm" id="h1" maxlength="200" required />
          
          <label><b>Costos</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="costos de los servicios" id="i1" maxlength="45" required />
          <label><b>Contacto</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="nombre completo" id="j1" maxlength="200" required />
          
          <label><b>Legalidad por México</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="text" placeholder="escribe tu correo" id="k1" maxlength="45"  />
          
          
          <label><b>Ubicación mapa</b></label>
          <textarea rows="5" cols="15" class="w3-input w3-border w3-margin-bottom" placeholder="escribe el texto que aparece en google maps" id="l1" maxlength="500"> </textarea>

          <button id="btnModify" class="w3-button w3-block w3-indigo w3-section w3-padding" type="button">Guardar</button>
        </div>
      </form>
      </section>
        <footer class="w3-center">Legalidad por México - Directorio de Instituciones</footer>
    </div>
  </div>
</div>


</body>
</html>

<?php
include "ABM_Titulos_C.php";
$msg422 = consTitulos($dbConn, 422);

?>

<script type="text/javascript">
$(document).ready(function() {
  $('#datatablesSimple').DataTable({
    "language": {
      "autoFill": {
        "cancel": "Cancelar",
        "fill": "Llenar las celdas con <i>%d<i><\/i><\/i>",
        "fillHorizontal": "Llenar las celdas horizontalmente",
        "fillVertical": "Llenar las celdas verticalmente"
      },
      "decimal": ",",
      "emptyTable": "No hay datos disponibles en la Tabla",
      "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
      "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
      "infoFiltered": "Filtrado de _MAX_ entradas totales",
      "infoThousands": ".",
      "lengthMenu": "Mostrar _MENU_ entradas",
      "loadingRecords": "Cargando...",
      "paginate": {
        "first": "Primera",
        "last": "Ultima",
        "next": "Siguiente",
        "previous": "Anterior"
      },
      "processing": "Procesando...",
      "search": "Búsqueda:",
      "searchBuilder": {
        "add": "Agregar condición",
        "button": {
          "0": "Constructor de búsqueda",
          "_": "Constructor de búsqueda (%d)"
        },
        "clearAll": "Quitar todo",
        "condition": "Condición",
        "conditions": {
          "date": {
            "after": "Luego",
            "before": "Luego",
            "between": "Entre",
            "empty": "Vacio",
            "equals": "Igual"
          }
        },
        "data": "Datos",
        "deleteTitle": "Borrar regla de filtrado",
        "leftTitle": "Criterio de alargado",
        "logicAnd": "Y",
        "logicOr": "O",
        "rightTitle": "Criterio de endentado",
        "title": {
          "0": "Constructor de búsqueda",
          "_": "Constructor de búsqueda (%d)"
        },
        "value": "Valor"
      },
      // "searchPlaceholder": "Buscar",
      "searchPlaceholder": "<?php echo $msg422 ?>",
      "thousands": ".",
      "zeroRecords": "No se encontraron registros que coincidan con la búsqueda",
      "datetime": {
        "previous": "Anterior",
        "next": "Siguiente",
        "hours": "Hora",
        "minutes": "Minuto",
        "seconds": "Segundo"
      },
      "editor": {
        "close": "Cerrar",
        "create": {
          "button": "Nuevo",
          "title": "Crear nueva entrada",
          "submit": "Crear"
        },
        "edit": {
          "button": "Editar",
          "title": "Editar entrada",
          "submit": "Actualizar"
        },
        "remove": {
          "button": "Borrar",
          "title": "Borrar",
          "submit": "Borrar",
          "confirm": {
            "_": "Está seguro que desea borrar %d filas?",
            "1": "Está seguro que desea borrar 1 fila?"
          }
        },
        "multi": {
          "title": "Múltiples valores",
          "info": "La selección contiene diferentes valores para esta entrada. Para editarla y establecer todos los items al mismo valor, clickear o tocar aquí, de otra manera conservarán sus valores individuales.",
          "restore": "Deshacer cambios",
          "noMulti": "Esta entrada se puede editar individualmente, pero no como parte de un grupo."
        }
      }
    }
  });
});
</script>


// window.addEventListener('DOMContentLoaded', event => {
//     // Simple-DataTables
//     // https://github.com/fiduswriter/Simple-DataTables/wiki
//
//     const datatablesSimple = document.getElementById('datatablesSimple');
//     if (datatablesSimple) {
//         new simpleDatatables.DataTable(datatablesSimple);
//     }
//
//
// });

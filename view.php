<head>
  <title>Faculty  Activities Details</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <script>
    jQuery(document).ready(function() {
        jQuery(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');   
    });
  </script>
  <style>
    .table-scroll {
      *position:relative;
      margin:auto;
      overflow:hidden;
      border:1px solid #000;
    }
    .table-wrap {
      width:100%;
      overflow:auto;
    }
    .table-scroll table {
      width:100%;
      margin:auto;
      border-spacing:0;
    }
    .table-scroll th, .table-scroll td {
      padding:15px 15px;
      border:1px solid #000;
      background:#fff;
      white-space: pre-line;
      vertical-align:top;
    }
    .table-scroll thead, .table-scroll tfoot {
      background:#f9f9f9;
    }
    .clone {
      position:absolute;
      top:0;
      left:0;
      pointer-events:none;
    }
    .clone th, .clone td {
      visibility:hidden;
      white-space: pre-line;

    }
    .clone td, .clone th {
      border-color:transparent;
      white-space: pre-line;
    }
    .clone tbody th {
      visibility:visible;
      color:red;
      border:1px solid #000;
      white-space: pre-line;
    }
    .clone .fixed-side {
      border:2px solid #000;
      background:#fff;
      visibility:visible;
      position: relative;
      margin-top: 5px;
      padding: 15px 15px; 
      white-space: pre-line;
    }
    .clone thead, .clone tfoot{
      background:transparent;
      border:1px solid #000;
    }
  </style>
</head>
<style>
table.dataTable{
	clear:both;
	margin-top:6px !important;
	margin-bottom:6px !important;
	max-width:none !important;
	border-collapse:separate !important;
	border-spacing:0
}
table.dataTable td,table.dataTable th{
	-webkit-box-sizing:content-box;
	box-sizing:content-box
}
table.dataTable td.dataTables_empty,table.dataTable th.dataTables_empty{
	text-align:center
}
table.dataTable.nowrap th,table.dataTable.nowrap td{
	white-space:nowrap
}
div.dataTables_wrapper div.dataTables_length label{
	font-weight:normal;
	text-align:left;
	white-space:nowrap
}
div.dataTables_wrapper div.dataTables_length select{
	width:auto;
	display:inline-block
}
div.dataTables_wrapper div.dataTables_filter{
	text-align:right
}
div.dataTables_wrapper div.dataTables_filter label{
	font-weight:normal;
	white-space:nowrap;
	text-align:left
}
	div.dataTables_wrapper div.dataTables_filter input{
		margin-left:0.5em;
		display:inline-block;
		width:auto
}
div.dataTables_wrapper div.dataTables_info{
	padding-top:0.85em;
	white-space:nowrap
}
div.dataTables_wrapper div.dataTables_paginate{
	margin:0;
	white-space:nowrap;
	text-align:right
}
div.dataTables_wrapper div.dataTables_paginate ul.pagination{
	margin:2px 0;
	white-space:nowrap;
	justify-content:flex-end
}
div.dataTables_wrapper div.dataTables_processing{
	position:absolute;
	top:50%;
	left:50%;
	width:200px;
	margin-left:-100px;
	margin-top:-26px;
	text-align:center;
	padding:1em 0
}
table.dataTable thead>tr>th.sorting_asc,table.dataTable thead>tr>th.sorting_desc,table.dataTable thead>tr>th.sorting,table.dataTable thead>tr>td.sorting_asc,table.dataTable thead>tr>td.sorting_desc,table.dataTable thead>tr>td.sorting{
		padding-right:30px
	}
table.dataTable thead>tr>th:active,table.dataTable thead>tr>td:active{
	outline:none
}
table.dataTable thead .sorting,table.dataTable thead .sorting_asc,table.dataTable thead .sorting_desc,table.dataTable thead .sorting_asc_disabled,table.dataTable thead .sorting_desc_disabled{
	cursor:pointer;
	position:relative
}
table.dataTable thead .sorting:before,table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:before,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:before,table.dataTable thead .sorting_desc:after,table.dataTable thead .sorting_asc_disabled:before,table.dataTable thead .sorting_asc_disabled:after,table.dataTable thead .sorting_desc_disabled:before,table.dataTable thead .sorting_desc_disabled:after{position:absolute;bottom:0.9em;display:block;opacity:0.3}
table.dataTable thead .sorting:before,table.dataTable thead .sorting_asc:before,table.dataTable thead .sorting_desc:before,table.dataTable thead .sorting_asc_disabled:before,table.dataTable thead .sorting_desc_disabled:before{right:1em;content:"\2191"}
table.dataTable thead .sorting:after,table.dataTable thead .sorting_asc:after,table.dataTable thead .sorting_desc:after,table.dataTable thead .sorting_asc_disabled:after,table.dataTable thead .sorting_desc_disabled:after{right:0.5em;content:"\2193"}
table.dataTable thead .sorting_asc:before,table.dataTable thead .sorting_desc:after{
	opacity:1
}
table.dataTable thead .sorting_asc_disabled:before,table.dataTable thead .sorting_desc_disabled:after{
	opacity:0
}
div.dataTables_scrollHead table.dataTable{
	margin-bottom:0 !important
}
div.dataTables_scrollBody table{
	border-top:none;
	margin-top:0 !important;
	margin-bottom:0 !important
}
div.dataTables_scrollBody table thead .sorting:before,div.dataTables_scrollBody table thead .sorting_asc:before,div.dataTables_scrollBody table thead .sorting_desc:before,div.dataTables_scrollBody table thead .sorting:after,div.dataTables_scrollBody table thead .sorting_asc:after,div.dataTables_scrollBody table thead .sorting_desc:after{display:none}
div.dataTables_scrollBody table tbody tr:first-child th,div.dataTables_scrollBody table tbody tr:first-child td{
	border-top:none
}
div.dataTables_scrollFoot>.dataTables_scrollFootInner{
	box-sizing:content-box
}
div.dataTables_scrollFoot>.dataTables_scrollFootInner>table{
	margin-top:0 !important;
	border-top:none
}
@media screen and (max-width: 767px){
	div.dataTables_wrapper div.dataTables_length,div.dataTables_wrapper div.dataTables_filter,div.dataTables_wrapper div.dataTables_info,div.dataTables_wrapper div.dataTables_paginate{
		text-align:center
	}
}
table.dataTable.table-sm>thead>tr>th{
	padding-right:20px
	}
table.dataTable.table-sm .sorting:before,table.dataTable.table-sm .sorting_asc:before,table.dataTable.table-sm .sorting_desc:before{
	top:5px;
	right:0.85em
}
table.dataTable.table-sm .sorting:after,table.dataTable.table-sm .sorting_asc:after,table.dataTable.table-sm .sorting_desc:after{
	top:5px
}
table.table-bordered.dataTable th,table.table-bordered.dataTable td{
	border-left-width:0
}
table.table-bordered.dataTable th:last-child,table.table-bordered.dataTable th:last-child,table.table-bordered.dataTable td:last-child,table.table-bordered.dataTable td:last-child{
	border-right-width:0
}
table.table-bordered.dataTable tbody th,table.table-bordered.dataTable tbody td{
	border-bottom-width:0
}
div.dataTables_scrollHead table.table-bordered{
	border-bottom-width:0
}
div.table-responsive>div.dataTables_wrapper>div.row{
	margin:0
}
div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:first-child{
	padding-left:0
}
div.table-responsive>div.dataTables_wrapper>div.row>div[class^="col-"]:last-child{
	padding-right:0
}
</style
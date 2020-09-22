const mix = require('laravel-mix');


mix
	.copy('node_modules/moment/min', 'src/public/crud/moment/min')
	.copy('node_modules/select2/dist', 'src/public/crud/select2/dist')
	.copy('node_modules/tinymce', 'src/public/crud/tinymce')
	.copy('node_modules/nestedSortable', 'src/public/crud/nestedSortable')
	.copy('node_modules/datatables.net', 'src/public/crud/datatables.net')
;


mix.copyDirectory('src/public', '../../../public')

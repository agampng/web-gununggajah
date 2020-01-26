var tinyConfig = {
    selector: "cmsimple-editor",
    skin: "cmsimple",
    block_formats: "Header 1=h1;Header 2=h2;Header 3=h3;Header 4=h4;Header 5=h5;Header 6=h6;Div=div;Paragraph=p;code=code;pre=pre",
    toolbar_items_size: "small",
    fontsize_formats: "8px 10px 12px 14px 15px 16px 18px 20px 24px 26px 30px 36px",
    entity_encoding : "named",
    entities : "160,nbsp",
    element_format : "html",
    extended_valid_elements: "script[type|language|src]",
    autosave_ask_before_unload: true,
    plugins: [
        "autosave save advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "media table contextmenu paste importcss emoticons"
    ],
    image_description: true,
	image_title: true,
    image_dimensions: false,
    image_advtab: true,
/*
	image_class_list: [
        {title: 'Keine Klasse', value: ''},
        {title: 'left border', value: 'tmce_left tmce_border'},
        {title: 'left no border', value: 'tmce_left tmce_noborder'},
        {title: 'right border', value: 'tmce_right tmce_border'},
        {title: 'right no border', value: 'tmce_right tmce_noborder'},
        {title: 'centered border', value: 'tmce_centered tmce_border'},
        {title: 'centered no border', value: 'tmce_centered tmce_noborder'},
    ],
*/
    importcss_append: false,
    importcss_groups: [
        {title: "Table styles", filter: /^(td|tr|table)\./},
        {title: "Block styles", filter: /^(div|p|ul|ol|h1|h2|h3|h4|h5|h6)\./},
        {title: "Image styles", filter: /^(img)\./},
        {title: "Other styles"} // The rest
    ],
	menu: {
        edit: {title: "Edit", items: "cut copy paste pastetext searchreplace | undo redo | selectall"},
        insert: {title: "Insert", items: "image link anchor | charmap hr nonbreaking"},
        view: {title: "View", items: "visualaid visualblocks visualchars | fullscreen code"},
        format: {title: "Format", items: "formats | bold italic underline strikethrough superscript subscript | removeformat"},
        table: {title: "Table", items: "inserttable tableprops deletetable | cell row column"},
        tools: {title: "Tools", items: "spellchecker code"}
    },
    menubar: "edit format insert view table",
    toolbar: "save code fullscreen searchreplace | formatselect | bullist numlist | link unlink | emoticons | undo redo",
	relative_urls: false,
	remove_script_host: false
};
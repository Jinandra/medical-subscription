(function (
UCODES_MAPPING,
COLLECTIONS_MAPPING,
CSRF_TOKEN,
URL_UCODES
) {
    // Get & set collections mapping
    function gc(id) {
        return UCODES_MAPPING['ucodes-' + id];
    }
    function sc(id, data) {
        UCODES_MAPPING['ucodes-' + id] = data;
    }
    
    function onCreatedFolder (newFolder) {
      COLLECTIONS_MAPPING['collection-'+newFolder.id] = newFolder;
    }
    
    $(document.body).on('click', '.btnSaveTo', function (e) {
        e.preventDefault();
        var id = $(e.target).data('value');
        var ucode = $.extend(true, {}, gc(id));
        
        if (ucode.media.length === 0) {
            ENFOLINK.modal.showEmptySelection();
        } else {
            var data = {
                onCreatedFolder: onCreatedFolder,
                ucodes: [ucode],
                targets: sortByStringField(objectToArray(COLLECTIONS_MAPPING), 'name'),
            };
            ENFOLINK.modal.showSaveTo(data);
        }
    });
    
    // Delete single collection handler
    $(document.body).on('click', '.btnDeleteUcode', function (e) {
        e.preventDefault();
        var id = $(e.target).data('value');
        var ucode = $.extend(true, {}, gc(id));
        if (ucode.media.length > 0) {
            ucode.media.push('self');
        }
        ENFOLINK.modal.showUcodeDelete({
            ucodes: [ucode]
        });
    });
})(
window.UCODES_MAPPING,
window.COLLECTIONS_MAPPING,
window.CSRF_TOKEN,
window.URL_UCODES
);
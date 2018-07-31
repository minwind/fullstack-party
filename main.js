//=include ../../../../../bower_components/jquery/dist/jquery.js

$( document ).ready(function() {

    $('#app_bundle_issue_type_choiceField').change(function(){
        $(this).closest('form').trigger('submit');
    });

    $(window).load(function(){ // On load
        var calcHeight = $('.issue-box').height() + $('.header-box').height() + 150;
        if ( $( ".issues-container" ).length ) {
            calcHeight = $('.custom-radio-box').height() + $('.header-box').height() + $('.pagination-box').height() + 50;
        }
        $('.issues-container, .comments').css({'height':(($(window).height() - calcHeight))+'px'});
    });
    $(window).resize(function(){ // On resize
        var calcHeight = $('.issue-box').height() + $('.header-box').height() + 150;
        if ( $( ".issues-container" ).length ) {
            calcHeight = $('.custom-radio-box').height() + $('.header-box').height() + $('.pagination-box').height() + 50;
        }
        $('.issues-container, .comments').css({'height':(($(window).height() - calcHeight))+'px'});
    });
});

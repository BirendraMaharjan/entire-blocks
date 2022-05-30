import $ from 'jquery';
import { useSelect } from '@wordpress/data';
/*import { useBlockProps } from '@wordpress/block-editor';*/
/*
const allPosts = useSelect((select) => {
	return wp.data.select('core/block-editor').getBlockAttributes('create-block/agutenblog').attributes;
});

console.log(allPosts);
debugger;*/
$(function ($) {
	/* $('body').on('click', 'a.page-numbers', function (e){
        e.preventDefault();
        console.log(myAjax.ajaxurl);
        $.ajax({
            type: "post",
            dataType: "json",
            url: '/getPosts/v1/getHTML',
            data: {
                action: "load_ajax_archive_list",
                /!* current_category_id: current_category_id,
                 feature_post_ids: feature_post_ids,
                 per_page: per_page,
                 pageNumber: pageNumber*!/
            },
            beforeSend: function() {
                /!*loadMoreButton.addClass('loading').html('+ Load More...');
                targetSection.addClass('loading-section');*!/
            },
            success: function(response) {
                /!*loadMoreButton.attr('data-pageNumber', (pageNumber + 1));
                loadMoreButton.closest('div').before(response);

                if (response === '') {
                    loadMoreButton.hide();
                } else {
                    loadMoreButton.show();
                }
    *!/
                $('.list-items').append(response.data.data);
                console.log(response);
            },
            complete: function() {
                /!* loadMoreButton.removeClass('loading').html('+ Load More');
                 targetSection.removeClass('loading-section');*!/
            },
            error: function(xhr) {
                // if error occured
                alert("Error occured. Please try again");
                console.log(xhr.statusText + xhr.responseText);
            },
        });
    });*/
});

function getResults() {
	$.when($.getJSON('http://localhost/wordpress/gutenberg/wordpress/wp-json/getPosts/v1/getHTML?id=63')).then(
		(posts) => {
			//	$('.list-items').append(posts.data.posts);
			$('.list-items').append(posts.posts);
			console.log(posts);
		},
		() => {
			console.log(`<p>Unexpected error; Please try again.</p>`);
		}
	);

	/* this.resultsDiv.html("Imagine real search results here...");
                    this.isSpinnerVisible = false;*/
}
/*
$('body').on('click', 'a.page-numbers', function (e) {
	e.preventDefault();
	getResults();
});
*/

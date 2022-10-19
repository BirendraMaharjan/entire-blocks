import $ from 'jquery';
import { useSelect } from '@wordpress/data';

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
$('body').on('click', 'a.page-numbers', function (e) {
	e.preventDefault();
	getResults();
});

# Pico Search Plugin

This is a basic search plugin for [Pico CMS](http://pico.dev7studios.com/). I built it to suit my own needs but it should work for most other basic sites as well.

## What It Does

It provides a basic search form for your templates as well as an integer of the total number of results and an array of results to display.

## Installation

Copy the `pico_search.php` file into the `plugins` folder in the root of Pico.

You'll need to create a `search` folder in your content and an `index.md` file within that so you can assign the template and keep the url clean for SEO.

Here's an example of my `content/search/index.md` file:

	/*
	Title: Search
	Template: search
	*/

I also recommend creating a search template in your theme to use for the results loop. Below are some sample code blocks from my `search.html` file:

	<h1>
		{% if pico_search.total_results > 1 %}
			{{ pico_search.total_results }} Results
		{% elseif pico_search.total_results == 1 %}
			1 Result
		{% else %}
			Nothing Found
		{% endif %}
	</h1>
	{% if pico_search.results|length == 0 %}
		<p>Try your search again?</p>
		{{ pico_search.search_form }}
	{% endif %}
	{% for page in pico_search.results %}
		<article>
			{% if page.thumbnail %}
				<figure class="shadowed"><img src="{{ page.thumbnail }}"></figure>
			{% endif %}
			<header class="article">
				<h1 class="h2"><a href="{{ page.url }}">{{ page.title }}</a></h1>
				{% if page.date %}
					<p><small>Posted: {{ page.date|date('F j, Y') }}</small></p>
				{% endif %}
			</header>
			{{ page.excerpt }}
			<footer class="article">
				<a class="btn" href="{{ page.url }}">Read More</a>
			</footer>
		</article>
	{% endfor %}

## Theme Options

The form is placed using `{{ pico_search.search_form }}`.

Total results are stored in `{{ pico_search.total_results }}`.

The loop is stored in `{{ pico_search.results }}`.

## Licence

MIT license: [MIT](http://opensource.org/licenses/MIT)

You are free to share & remix this code only if you mention me as coder of this base.


## Copyright

© Copyright Jeremy Hixon 2014. All rights reserved.

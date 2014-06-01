Thumbnails
==========

Creates image thumbnails on the fly and caches results for future queries.

Again, pre-existing code of mine from a larger project I had to prune down to the bare minimum. This code allows you
to create thumbnails on the fly, by setting a width and height as query parameters. Once the thumbnail is created, the
results are cached in a temporary directory. Then, if the page is reloaded, the already created cached thumbnail is used
instead of recreating the thumbnail again, allowing for faster page loading.

I've found this code very useful over the years, much better than having to create pre-sized thumbnails, and then having
to tailor your site around them.

An example usage would be as follows:

```html
<img src="/path/to/code/thumbnail.php?file=relative_path_to_image.jpg&w=100&h=100" alt="" />
```

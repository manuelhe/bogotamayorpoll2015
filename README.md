#Poll results consolidation page

Just a simple application to show the results with graphs.


## General App details

The only and the most important external library used in this application was [Google Graphs](https://developers.google.com/chart/). The API is just a plain old PHP class and the UI interaction, aside the graphs, is also plain Javascript.

Initially I wanted to get connected with the Google Apps API to get the results directly from the spreadsheet, but I didn't have enough time to spend in this futile task. A download as CSV option was enough and I also wanted to check for duplicates, usually a double click in the submit button; I've got at least 5 or 6 of them.

## Rants

I know. Everything in just one file? That's awful but, who cares?. A PHP class, the HTML template, the Javascript application, the styles.

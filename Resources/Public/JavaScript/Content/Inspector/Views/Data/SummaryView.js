define(
  [
    'Library/jquery-with-dependencies',
    'emberjs',
    'Content/Inspector/InspectorController',
    'Content/Inspector/Views/Widget',
    'Content/Inspector/Views/Data/DataSourceLoader',
    'Shared/HttpClient',
    'text!./SummaryView.html'
  ],
  function ($,
            Ember,
            InspectorController,
            Widget,
            DataSourceLoader,
            HttpClient,
            template) {
    /**
     * Widget that displays data in columns with an optional large hero column
     */
    return Widget.extend(DataSourceLoader, {
      template: Ember.Handlebars.compile(template),

      classNames: ['ttree-neos-tone-summaryview'],
      classNameBindings: ['_columnsClass'],

      // Column definitions
      columns: null,
      // Single, large hero definition
      hero: null,

      // Load data from the data source
      _loadData: function() {
        var that = this,
          nodePath = InspectorController.nodeSelection.get('selectedNode.nodePath');

        var dataSourceUri = HttpClient._getEndpointUrl('neos-data-source') + '/' + this.get('dataSource'),
          entity = InspectorController.nodeSelection.get('selectedNode._vieEntity'),
          typoScriptPath = entity.get('typo3:__typoscriptPath'),
          arguments = this.get('arguments') || {};
        
        arguments.node = nodePath;
        arguments.typoscriptPath = typoScriptPath;

        HttpClient.getResource(dataSourceUri + '?' + $.param(arguments), {dataType: 'json'}).then(
          function(results) {
            if (results.error) {
              that.set('error', results.error);
            } else {
              that.set('data', results.data);
            }
            that.set('isLoading', false);
          }
        );
      }.on('init'),

      _columnValues: function () {
        var columnValues = [],
          data = this.get('data'),
          columns = this.get('columns');
        if (!data) {
          return [];
        }
        $.each(columns, function () {
          columnValues.push($.extend({
            value: Ember.get(data, this.data),
            width: Ember.get(data, this.data) * 100 + '%'
          }, this));
        });
        return columnValues;
      }.property('columns', 'data'),

      _heroValue: function () {
        var data = this.get('data'),
          hero = this.get('hero');
        if (data && hero) {
          return {
            label: hero.label,
            value: Ember.get(data, hero.data)
          };
        } else {
          return null;
        }
      }.property('hero', 'data'),

      _columnsClass: function () {
        var columns = this.get('columns');
        return columns && columns.length ? 'ttree-neos-tone-columnview-columns-' + columns.length : '';
      }.property('columns')
    });
  });

define(
  [
    'Library/jquery-with-dependencies',
    'emberjs',
    'Content/Inspector/InspectorController',
    'Content/Inspector/Views/Widget',
    'Content/Inspector/Views/Data/DataSourceLoader',
    'Shared/HttpClient',
    './SummaryBlockView',
    'text!./SummaryView.html'
  ],
  function ($,
            Ember,
            InspectorController,
            Widget,
            DataSourceLoader,
            HttpClient,
            SummaryBlockView,
            template) {
    /**
     * Widget that displays tone analysis summary
     */
    return Widget.extend(DataSourceLoader, {
      template: Ember.Handlebars.compile(template),

      classNames: ['ttree-neos-tone-summaryview__container'],

      // Column definitions
      blocks: null,

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

      blockView: function () {
        return Ember.CollectionView.extend({
          classNames: ['ttree-neos-tone-summaryview__wrapper'],
          content: this.get('data'),
          itemViewClass: SummaryBlockView,
          emptyView: Ember.View.extend({
            template: Ember.Handlebars.compile('{{view._parentView._parentView._loadingLabel}}'),
            tagName: 'span',
            classNames: ['neos-inspector-file-loading']
          })
        });
      }.property('blocks', 'data')
    });
  });

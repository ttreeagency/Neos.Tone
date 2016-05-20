define(
  [
    'Library/jquery-with-dependencies',
    'emberjs',
    'Shared/I18n',
    'text!./SummaryBlockView.html'
  ],
  function ($,
            Ember,
            I18n,
            template) {
    /**
     * Widget that displays tone analysis summary block
     */
    return Ember.View.extend({
      template: Ember.Handlebars.compile(template),

      label: null,
      mainTone: null,

      didInsertElement: function () {
        this.set('label', I18n.translate('Ttree.Neos.Tone:Main:' + this.get('content.label')));
        this.set('mainTone', I18n.translate('Ttree.Neos.Tone:Main:' + this.get('content.resume.mainTone')));
      },

      tones: function () {
        var tones = [];
        this.get('content.tones').map(function (item) {
          var value = item.value,
            classBinding = 'ttree-neos-tone-columnview-column__cursor',
            classLevelBinding = '';
          if (value >= 0.75) {
            classLevelBinding = 'ttree-neos-tone-columnview-column__cursor--high';
          } else if (value < 0.75 && value >= 0.50) {
            classLevelBinding = 'ttree-neos-tone-columnview-column__cursor--medium';
          } else {
            classLevelBinding = 'ttree-neos-tone-columnview-column__cursor--low';
          }

          classBinding += ' ' + classLevelBinding;

          tones.push({
            label: I18n.translate('Ttree.Neos.Tone:Main:' + item.label),
            value: item.value,
            classBinding: classBinding,
            styleBinding: 'width: ' + item.value * 100 + '%;'
          });
        });
        return tones;
      }.property('content.tones'),

      classNames: ['ttree-neos-tone-summaryview__summary']
    });
  });

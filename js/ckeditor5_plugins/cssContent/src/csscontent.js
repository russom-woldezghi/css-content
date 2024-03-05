import { Plugin } from 'ckeditor5/src/core';
import { createDropdown, addListToDropdown, Model } from 'ckeditor5/src/ui';
import { Collection } from 'ckeditor5/src/utils';


export default class CssContent extends Plugin {
  async init() {
    const editor = this.editor;
    const options_config = this.editor.config.get('css_content');
    console.log('Value: ', options_config['vocab']);
    
    const dropDownOptions = [
      'test_item',
      'test_item2'
    ];
    
    editor.ui.componentFactory.add('cssContent', function (locale) {

    const dropdownView = createDropdown(locale);
    dropdownView.buttonView.set({
      label: 'CSS Content',
      withText: true,
      // icon: optionsIcon,
      tooltip: true
    });
      addListToDropdown(dropdownView, createItems(['dropDownOptions']));
      dropdownView.listenTo(dropdownView, 'execute', _onexecute);
      dropdownView.render();
      return dropdownView;
    });

    //create the items for the dropdown menu
    const createItems = (dropDownOptions) => {
      const collection = new Collection();
        dropDownOptions.forEach(options => {
          const optionsElement = new Model({
            label: options.title,
            withText: true,
            tooltip: options.description || options.title,
            html: options.html
          });
          collection.add({
            type: 'button',
            model: optionsElement
          });
        });
        return collection;
    }

    //function that is executed when the button is clicked
    const _onexecute = (event) => {
      editor.model.change( writer => {

        const options = dropDownOptions.find(options => options.title === event.source.label);
        const viewFragment = editor.data.processor.toView(options.html);
        const modelFragment = editor.data.toModel(viewFragment);
        editor.model.insertContent(modelFragment, editor.model.document.selection);
        
      });
    }
  }
}

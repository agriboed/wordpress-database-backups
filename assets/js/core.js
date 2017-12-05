/**
 * global document, $
 */
class App {
  /**
   *
   */
  constructor() {
    this.key = window.DatabaseBackups.key;
    this.url = window.DatabaseBackups.admin_url;
    this.nonce = window.DatabaseBackups.nonce;

    this.container = $('.database-backups');
    this.
    //  try {
    this.init();
    // } catch (e) {
    //    console.log('Error:' + e);
    //   }
  }

  /**
   * @param selector
   * @return object
   */
  getElement(selector) {
    return this.container.find(selector);
  }

  /**
   * @return App
   */
  init() {
    this.bindCreate();
    return this;
  }

  /**
   * @return App
   */
  bindCreate() {
    let createButton = this.getElement('button.create');
      createButton.click( () =>  {
        this.createBackupCall();
    });
    return this;
  }

  createBackupCall() {
      $.ajax({
          type: 'POST',
          url: admin_url,
          data: {
            'action': 'database-backups_create',
            'do_backup': true,
          },
          success: function(data){

          }
      });
  }
}

(function($) {
  $(document).ready(() => {
    new App();
  });
}(jQuery));
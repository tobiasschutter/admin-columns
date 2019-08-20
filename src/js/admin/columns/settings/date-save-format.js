class SettingDateSaveFormat {
	constructor( column ) {
		this.column = column;

		this.column = column;
		this.setting = column.$el[ 0 ].querySelector( '.ac-column-setting--date_save_format' );

		if ( !this.setting ) {
			return;
		}

		this.initHelp();
		this.bindEvents();
	}

	getValue(){
		return this.setting.querySelector('select').value;
	}

	initHelp(){
		if( '' === this.getValue() ){
			this.setting.querySelector('.help-msg' ).style.visible = 'visible';
		}else {
			this.setting.querySelector('.help-msg' ).style.visible = 'hidden';
		}
	}

	initExample(){
		if( '' === this.getValue() ){
			this.setting.querySelector('.col.-date' ).style.display = 'block';
		}else {
			this.setting.querySelector('.col.-date' ).style.display = 'none';
		}
	}

	bindEvents() {
		const select = this.setting.querySelector( 'select' );
		select.addEventListener( 'change', e => {
			this.getDateExample( select.value ).done( d => {
				this.setting.querySelectorAll( '[data-date-example]' ).forEach( e => {
					e.innerText = d;
				} );
			} );
			this.initHelp();
		} );
	}

	createExample() {
		this.setting.querySelector( '.ac-setting-input' ).insertAdjacentHTML( 'beforeend', '<div class="test"">AAP</div>' );
	}

	getDateExample( format ) {
		return jQuery.ajax( {
			url : ajaxurl,
			method : 'post',
			data : {
				action : 'date_format',
				date : format
			}
		} );
	}

}

let date_save_format = function( column ) {
	column.settings.date_save_format = new SettingDateSaveFormat( column );
};

module.exports = date_save_format;
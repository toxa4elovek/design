class ProfSelectBox extends  React.Component{
    constructor() {
        super();
        this.onChange = this.onChange.bind(this);
    }
    onChange() {
        const input = this.refs.radioInput;
        const value = input.checked ? 1 : 0;
        const info = this.props.data;
        const sectionJqueryObject = $('.user-company-section');
        let data = {};
        if(('is_company' === info.name) && (input.checked)) {
            sectionJqueryObject.show();
        }else {
            sectionJqueryObject.hide();
        }
        data[info.name] = value;
        $.post('/users/update.json', data);
    }
    render() {
        const info = this.props.data;
        let checked = false;
        if(info.isDesigner == 1) {
            checked = true;
        }
        const input = <input ref="radioInput" onChange={this.onChange} type="radio" name="prof" className={info.name} value="1" defaultChecked={checked} />;
        return (
            <div className="radio-container" style={{"marginLeft": info['margin-left']}}>
                <label>{input}{info.title}</label>
                <input type="hidden" name={info.name} value="0" />
            </div>
        )
    }
}
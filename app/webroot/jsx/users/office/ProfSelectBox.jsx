class ProfSelectBox extends  React.Component{
    constructor() {
        super();
        this.onChange = this.onChange.bind(this);
    }
    onChange() {
        const input = this.refs.radioInput;
        const value = input.checked ? 1 : 0;
        const info = this.props.data;
        const userCompanySection = $('.user-company-section');
        const userDetailsSection = $('.user-details-section');
        let data = {};
        console.log('change');
        if(('is_company' === info.name) && (input.checked)) {
            userCompanySection.show();
            $.scrollTo(userCompanySection, {
                axis: 'y',
                duration: 500
            });
        }else {
            userCompanySection.hide();
        }
        if((('isDesigner' === info.name) || ('isCopy' === info.name)) && (input.checked)) {
            userDetailsSection.show();
        }else {
            userDetailsSection.hide();
        }
        data[info.name] = value;
        $.post('/users/update.json', data);
    }
    render() {
        const info = this.props.data;
        let checked = false;
        if(1 == info.isDesigner) {
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
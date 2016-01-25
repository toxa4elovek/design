class ProjectTypesRadio extends React.Component {
    onChange (e) {}
    render () {
        const info = this.props.data;
        let checked = false;
        if(info.checked) {
            checked = true;
        }
        const radioStyle = {
            "width": "24px",
            "height": "14px",
            "verticalAlign": "middle",
            "boxShadow": "none !important"
        };
        const input = <input className={info.name}
             defaultChecked={checked}
             name="subscription-type"
             onChange={this.onChange}
             ref="radioInput"
             style={radioStyle}
             type="radio"
             value={info.value}
                      />;
        const labelStyle = {
            "textShadow": "-1px 0 0 #FFFFFF",
            "marginTop": "6px",
            "fontSize": "12px",
            "lineHeight": "16px",
            "color": "#757575"
        };
        return (
            <div className="radio-container" style={{"float": "left", "marginLeft": 0, "width": "170px", "paddingTop": "5px"}}>
                <label style={labelStyle}>{input}<span>{info.label}</span></label>
                <input type="hidden" name="subscription-type" value={info.value} />
            </div>
        );
    }
}
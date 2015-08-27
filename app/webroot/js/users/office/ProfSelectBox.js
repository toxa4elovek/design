var ProfSelectBox = new React.createClass({
    onChange: function(e) {
        var input = React.findDOMNode(this.refs.radioInput);
        var value = input.checked ? 1 : 0;
        var data = {};
        var info = this.props.data;
        if((info.name == 'is_company') && (input.checked)) {
            $('.user-company-section').show();
        }else {
            $('.user-company-section').hide();
        }
        data[info.name] = value;
        $.post('/users/update.json', data);
    },
    render: function() {
        var info = this.props.data;
        var checked = false;
        if(info.isDesigner == 1) {
            checked = true;
        }
        var input = React.createElement("input", {ref: "radioInput", onChange: this.onChange, type: "radio", name: "prof", className: info.name, value: "1", defaultChecked: checked});
        return (
            React.createElement("div", {className: "radio-container", style: {"marginLeft": info['margin-left']}}, 
                React.createElement("label", null, input, info.title), 
                React.createElement("input", {type: "hidden", name: info.name, value: "0"})
            )
        )
    }
});
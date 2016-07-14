class PhoneNumberInput extends React.Component {
    constructor() {
        super()
        this.placeholderValue = '7 911 123 45 67'
    }
    onBlur(e) {
        if(e.target.value === '') {
            e.target.value = this.placeholderValue
        }
        const currentValue = e.target.value
        PaymentActions.submitNewPhoneNumber(currentValue)
    }
    onFocus(e) {
        if(e.target.value == this.placeholderValue) {
            e.target.value = '';
        }
        return true;
    }
    onChange(e) {
        let newValue = e.target.value;
        PaymentActions.updatePhoneNumberInput(newValue)
    }
    render() {
        const initialValue = this.props.phoneNumber
        let styles = {'color': '#4f5159'}
        if(initialValue === this.placeholderValue) {
            styles = {'color': '#ccc'}
        }
        return(
            <div>
                <input style={styles} ref='input' type='text' onChange={this.onChange.bind(this)} onFocus={this.onFocus.bind(this)} onBlur={this.onBlur.bind(this)} defaultValue={initialValue} className='phone-number-input'/>
            </div>
        );
    }
}
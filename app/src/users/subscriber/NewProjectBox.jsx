class NewProjectBox extends React.Component{
    componentDidMount() {
        const input = $(this.refs.price);
        input.numeric(
            {
                "negative": false,
                "decimal": false
            }
        );
        this.minimalPrice = 500;
        this.defaultText = 'Бланк для письма';
        this.wrongInputClassName = 'wrong-input';
    }
    onBlurHandler(e) {
        if(e.target.value < this.minimalPrice) {
            e.target.value = this.minimalPrice;
        }
    }
    onChangeTitle() {
        const titleInput = this.refs.title;
        if(titleInput.value == this.defaultText) {
            $(titleInput).addClass(this.wrongInputClassName);
        }else {
            $(titleInput).removeClass(this.wrongInputClassName);
        }
    }
    onClickCreateProject(e) {
        e.preventDefault();
        const priceInput = this.refs.price;
        const titleInput = this.refs.title;
        const dateInput = this.refs.date;
        let validInputs = true;
        if(priceInput.value < this.minimalPrice) {
            validInputs = false;
            $(priceInput).addClass(this.wrongInputClassName);
            priceInput.focus();
        }
        if(titleInput.value == this.defaultText) {
            $(titleInput).addClass(this.wrongInputClassName);
            titleInput.focus();
            validInputs = false;
        }
        if(validInputs) {
            const queryString = '?reward=' + priceInput.value + '&title=' + titleInput.value + '&date=' + encodeURIComponent(dateInput.value);
            window.location = '/pitches/brief/20' + queryString;
        }
    }
    render() {
        return (
            <div>
                <label>Сумма вознаграждения победителю (от 500р)<span style={{
                "position": "absolute",
                "fontSize": "31px",
                "fontWeight": "bold",
                "right": "13px",
                "top": "10px"
                }}>*</span></label>

                <input ref="price" onBlur={this.onBlurHandler.bind(this)} className="price-input" type="text" name="price" placeholder="500" />
                <label>название проекта<span style={{
                "position": "absolute",
                "fontSize": "31px",
                "fontWeight": "bold",
                "right": "13px",
                "top": "10px"
                }}>*</span></label>

                <input ref="title" onChange={this.onChangeTitle.bind(this)} type="text" name="title" className="title-input" placeholder="Бланк для письма" />

                <div className="clear"></div>
                <label className="date-label">дата окончания приёма работ</label>
                <label className="time-label">время</label>
                <div className="clear"></div>

                <div style={{"position": "relative"}}>
                    <input ref="date" type="text" className="hidden-date-input" style={{"position": "absolute", "top": "0", "height": "50px","opacity": "0", "paddingBottom": "10px"}}/>
                    <input type="text" data-field="day" className="date-input" />
                    <input type="text" data-field="month" className="date-input" />
                    <input type="text" data-field="year" className="date-input year" />

                    <input type="text" data-field="hours" className="date-input" />
                    <input type="text" data-field="minutes" className="date-input last-block" />
                </div>

                <div className="clear"></div>

                <a href="#" onClick={this.onClickCreateProject.bind(this)} className="button silver-button clean-style-button create-project-button">создать проект</a>

            </div>
        )
    }
}
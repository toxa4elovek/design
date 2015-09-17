class NewProjectBox extends React.Component{
    minimalPrice = 500;
    componentDidMount() {
        const input = $(this.refs.award);
        input.numeric(
            {
                "negative": false,
                "decimal": false
            });
    }
    onBlurHandler(e) {
        if(e.target.value < this.minimalPrice) {
            e.target.value = this.minimalPrice;
        }
    }
    render() {
        return (
            <div>
                <label>Сумма вознаграждения победителю (от 500р)<span>*</span></label>

                <input ref="award" onBlur={this.onBlurHandler.bind(this)} className="price-input" type="text" name="price" placeholder="500" />
                <label>название проекта<span>*</span></label>

                <input type="text" name="title" className="title-input" placeholder="Бланк для письма" />

                <div className="clear"></div>
                <label className="date-label">дата окончания приёма работ</label>
                <label className="time-label">время</label>
                <div className="clear"></div>

                <input type="text" data-field="day" className="date-input" />
                <input type="text" data-field="month" className="date-input" />
                <input type="text" data-field="year" className="date-input year" />

                <input type="text" data-field="hours" className="date-input" />
                <input type="text" data-field="minutes" className="date-input last-block" />
                <div className="clear"></div>

                <a href="#" className="button silver-button clean-style-button create-project-button">создать проект</a>

            </div>
        )
    }
}
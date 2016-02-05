class ProjectTypesRadio extends React.Component {
    onChange (e) {
        let fileFormats = '';
        if(e.target.value === 'copyrighting') {
            fileFormats = `<li class="wide graysupplement"><label><input type="checkbox" name="" data-value="DOC" checked="">.DOC</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PDF" checked="">.PDF</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие">другие</label></li>`;
            $('.subscriber-qualities-title').text('Какие 3 качества нужно донести?');
            $('.subscriber-audience-title').text('Какими свойствами должен обладать копирайтинг?');
            $('.extensions').html(fileFormats);
        }else {
            fileFormats = `<li class="wide graysupplement"><label><input type="checkbox" name="" checked="" data-value="EPS">.EPS</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="AI">.AI (Illustrator)</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="JPG" checked="">.JPG</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="PNG" checked="">.PNG</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="PDF">.PDF</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="PSD">.PSD (Photoshop)</label></li>
                <li class="wide graysupplement"><label><input type="checkbox" name="" data-value="Indd">.Indd (In Design)</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="GIF">.GIF</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="TIFF">.TIFF</label></li>
                <li class="graysupplement"><label><input type="checkbox" name="" data-value="другие">другие</label></li>`;
            $('.subscriber-qualities-title').text('Какие 3 качества нужно донести через дизайн?');
            $('.subscriber-audience-title').text('Какими свойствами должен обладать ваш дизайн?');
            $('.extensions').html(fileFormats);
        }
    }
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
             onChange={this.onChange.bind(this)}
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
import { Fragment, useState, useEffect } from "@wordpress/element";
import { RangeControl, ColorPicker  } from '@wordpress/components';


export const textShadowSchema = {
    type: "object",
    default: {
        horizontal: 0,
        vertical: 0,
        blur: 0,
        color: ""
    }
}

export function convertTextShadow( textShadow ) {

    const {
        horizontal,
        vertical,
        blur,
        color
    } = textShadow;


    return `${horizontal}px ${vertical}px ${blur}px ${color}`;

}

function TextShadowPicker( props ) {


    const [ state, setState ] = useState(textShadowSchema.default);


    
    useEffect(() => {

        const { value } = props;
        
        setState( value ); 


    }, [])

    
    useEffect(() => {

        props.onChange( state );


    }, [state])

    return (
        <Fragment>
            <RangeControl

                label="Horizontal"
                value={ state.horizontal }
                onChange={ ( horizontal ) => setState( { ...state, horizontal } ) }
                min={ 0 }
                max={ 100 }
            />

            <RangeControl
                label="Vertical"
                value={ state.vertical }
                onChange={ ( vertical ) => setState( { ...state, vertical } ) }
                min={ 0 }
                max={ 100 }
            />

            <RangeControl
                label="Blur"
                value={ state.blur }
                onChange={ ( blur ) => setState( { ...state, blur } ) }
                min={ 0 }
                max={ 100 }
            />
            
            <ColorPicker
                color={ state.color }
                onChangeComplete={ ( color ) => setState( { ...state, color: color.hex } ) }
                disableAlpha
             />
        </Fragment>
    )
}

export default TextShadowPicker;
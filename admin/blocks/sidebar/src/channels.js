const { BaseControl, CheckboxControl } = window.wp.components;
const { useSelect, useDispatch } = window.wp.data;
const { useEffect } = window.wp.element;

const ChannelListControls = (props) => {
    // get the meta value
    let { checklistValues } = useSelect(select => {
        const metaValue = select('core/editor').getEditedPostAttribute('meta')[props.metaKey];
        return {
            checklistValues: metaValue || [],
        }
    });

    // Update parent component when checklistValues change
    useEffect(() => {
        props.channels(checklistValues);
    }, [checklistValues, props]);

    // a function that updates the meta value
    const { editPost } = useDispatch('core/editor');

    return (
        <BaseControl>
            {props.options.map((item, index) =>
                <CheckboxControl
                    key={index}
                    label={item}
                    checked={checklistValues.includes(item)}
                    onChange={(checked) => {
                        let updatedValues = checklistValues.filter(value => value !== item);
                        if (checked) {
                            updatedValues.push(item);
                        }
                        props.channels(updatedValues); // Update parent component
                        editPost({ meta: { [props.metaKey]: updatedValues } });
                    }}
                />
            )}
        </BaseControl>
    );
}

export default ChannelListControls;

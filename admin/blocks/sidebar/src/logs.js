import { BaseControl, Tooltip } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { Icon, check, cancelCircleFilled, backup, info } from '@wordpress/icons';

const LogsList = (props) => {

    // Function to get the correct icon based on the type
    const getIconByType = (type) => {
        if (type === 'success') {
            return check;
        } else if (type === 'error') {
            return cancelCircleFilled;
        }
        return null;
    };

    return (
        <BaseControl >
            <div >
            {props.logs.length === 0 ? (
                <p>{__('No logs available', 'teams-publisher')}</p>
            ) : (
                <ul className="mstp_logs">
                    {props.logs.map((item, index) => (
                        <li key={`${item.date}-${index}`} className={item.type}>
                            <Tooltip text={item.type}>
                                <Icon size="14" icon={getIconByType(item.type)} />
                            </Tooltip>
                            <Tooltip text={item.date}>
                                <Icon size="14" icon={backup}/>
                            </Tooltip>
                            <Tooltip text={item.message}>
                                <Icon size="14" icon={info} />
                            </Tooltip>
                            &nbsp;&nbsp;&nbsp;&nbsp;{item.channel}
                        </li>
                    ))}
                </ul>
            )}
            </div>
        </BaseControl>
    );
};

export default LogsList;

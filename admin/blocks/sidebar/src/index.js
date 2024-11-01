import ChannelListControls from "./channels";
import { __ } from '@wordpress/i18n';
import LogsList from "./logs";
import { PluginSidebar } from '@wordpress/edit-post';
import { registerPlugin } from '@wordpress/plugins';
import { useEffect, useState, useCallback } from 'react';
import { Flex, FlexItem, PanelBody, Button } from '@wordpress/components';

(function( wp ) {
    const { PluginSidebar } = wp.editPost;
    const { useSelect, dispatch } = wp.data;

    const TEAMS_PUBLISHERSidebar = () => {
        const [channels, setChannels] = useState([]);
        const [loading, setLoading] = useState(false);
        const [logs, setLogs] = useState([]);

        const metaLogs = useSelect((select) => {
            const meta = select('core/editor').getEditedPostAttribute('meta');
            const metaValue = meta ? meta['mstp_logs'] : [];
            return Array.isArray(metaValue) ? metaValue : []; // Ensure metaValue is an array
        });

        const postStatus = useSelect((select) => {
            return select('core/editor').getEditedPostAttribute('status');
        });

        const postId = useSelect((select) => {
            return select('core/editor').getCurrentPostId();
        });

        useEffect(() => {
            setLogs(metaLogs);
        }, [metaLogs]);

        const updateLogs = (result) => {
            setLogs(result);
            console.log(result);
        };

        const send = async () => {
            if (channels.length === 0) {
                alert(__('No channel selected!', 'teams-publisher'));
            } else {
                setLoading(true);
                try {
                    // Save the post before sending data
                    await dispatch('core/editor').savePost();

                    const response = await fetch(mstp_sidebar.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            channels: channels,
                            post_id: postId,
                        }),
                    });
                    const result = await response.json();
                    setLoading(false);
                    updateLogs(result);
                } catch (error) {
                    console.error('ERROR', error);
                    setLoading(false);
                }
            }
        };

        const getChannels = useCallback((chnl) => {
            setChannels(chnl);
        }, []);

        const redirectToAddChannel = () => {
            window.location.href = mstp_sidebar.url_settings; // Replace with your actual URL
        };

        // Check if the post is published before rendering the button
        if (postStatus !== 'publish') {
            return null;
        }

        return (
            <PluginSidebar name="teams-publisher-sidebar" title="Teams Publisher" icon="megaphone">
                <PanelBody title="Channels" initialOpen={ true }>
                    {mstp_sidebar.channels.length === 0 && (
                        <p>{__('Please register a channel', 'teams-publisher')}</p>
                    )}
                    <ChannelListControls
                        metaKey="mstp_channels"
                        label={ __('Select channels', 'teams-publisher') }
                        options={ mstp_sidebar.channels }
                        channels={ getChannels }
                    />
                    <hr/>
                    <Flex>
                        <FlexItem>
                            <Button variant="primary" icon="megaphone" onClick={send} isBusy={loading}>
                                {__('Publish', 'teams-publisher')}
                            </Button>
                        </FlexItem>
                        <FlexItem>
                            <Button variant="secondary" onClick={redirectToAddChannel}>
                                {__('Add Channel', 'teams-publisher')}
                            </Button>
                        </FlexItem>
                    </Flex>
                </PanelBody>
                <PanelBody title="Logs" initialOpen={ true }>
                    <LogsList logs={logs} />
                </PanelBody>
            </PluginSidebar>
        );
    };

    registerPlugin('teams-publisher-sidebar', {
        render: TEAMS_PUBLISHERSidebar,
    });
})(window.wp);
// External Dependencies
import React from 'react';

import AjaxComponent from './../base/AjaxComponent';
import './style.css';

class OpentableWidgetModule extends AjaxComponent {
    static slug = 'et_pb_df_opentable_widget';
    static global_module_index = 0;

    componentDidMount() {
        this.setState({
            module_index: OpentableWidgetModule.global_module_index
        });
        OpentableWidgetModule.global_module_index++;
        super.componentDidMount();
        window.initOpentable();
    }

    componentDidUpdate(prevProps) {
        super.componentDidUpdate(prevProps);
        window.initOpentable();
    }

    _shouldReload(oldProps, newProps) {
        return !this._compareObjects(newProps, oldProps);
    }

    _reloadData(props) {
        return {
            action: "et_fb_ajax_render_shortcode",
            et_pb_render_shortcode_nonce: window.ETBuilderBackend.nonces.renderShortcode,
            et_fb_module_index: this.state.module_index,
            options: {
                conditional_tags: window.ETBuilderBackend.conditionalTags,
                current_page: window.ETBuilderBackend.currentPage,
                post_type: window.ETBuilderBackend.postType
            },
            object: [{
                type: 'et_pb_df_opentable_widget',
                content: '',
                attrs: props,
                raw_child_content: ''
            }]
        };
    }

    render() {
        return super.render();
    }

    _render() {
        return (
            <div dangerouslySetInnerHTML={{ __html : this.state.result.data }} />
            );
    }

}

export default OpentableWidgetModule;

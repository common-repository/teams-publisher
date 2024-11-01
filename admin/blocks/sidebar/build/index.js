(()=>{"use strict";const e=window.React,{BaseControl:t,CheckboxControl:n}=window.wp.components,{useSelect:l,useDispatch:a}=window.wp.data,{useEffect:s}=window.wp.element,r=r=>{let{checklistValues:i}=l((e=>({checklistValues:e("core/editor").getEditedPostAttribute("meta")[r.metaKey]||[]})));s((()=>{r.channels(i)}),[i,r]);const{editPost:o}=a("core/editor");return(0,e.createElement)(t,null,r.options.map(((t,l)=>(0,e.createElement)(n,{key:l,label:t,checked:i.includes(t),onChange:e=>{let n=i.filter((e=>e!==t));e&&n.push(t),r.channels(n),o({meta:{[r.metaKey]:n}})}}))))},i=window.wp.i18n,o=window.wp.components,c=window.wp.primitives,m=(0,e.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,e.createElement)(c.Path,{d:"M16.7 7.1l-6.3 8.5-3.3-2.5-.9 1.2 4.5 3.4L17.9 8z"})),d=(0,e.createElement)(c.SVG,{viewBox:"0 0 24 24",xmlns:"http://www.w3.org/2000/svg"},(0,e.createElement)(c.Path,{d:"M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM15.5303 8.46967C15.8232 8.76256 15.8232 9.23744 15.5303 9.53033L13.0607 12L15.5303 14.4697C15.8232 14.7626 15.8232 15.2374 15.5303 15.5303C15.2374 15.8232 14.7626 15.8232 14.4697 15.5303L12 13.0607L9.53033 15.5303C9.23744 15.8232 8.76256 15.8232 8.46967 15.5303C8.17678 15.2374 8.17678 14.7626 8.46967 14.4697L10.9393 12L8.46967 9.53033C8.17678 9.23744 8.17678 8.76256 8.46967 8.46967C8.76256 8.17678 9.23744 8.17678 9.53033 8.46967L12 10.9393L14.4697 8.46967C14.7626 8.17678 15.2374 8.17678 15.5303 8.46967Z"})),h=window.wp.element,w=(0,h.forwardRef)((function({icon:e,size:t=24,...n},l){return(0,h.cloneElement)(e,{width:t,height:t,...n,ref:l})})),p=(0,e.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,e.createElement)(c.Path,{d:"M5.5 12h1.75l-2.5 3-2.5-3H4a8 8 0 113.134 6.35l.907-1.194A6.5 6.5 0 105.5 12zm9.53 1.97l-2.28-2.28V8.5a.75.75 0 00-1.5 0V12a.747.747 0 00.218.529l1.282-.84-1.28.842 2.5 2.5a.75.75 0 101.06-1.061z"})),u=(0,e.createElement)(c.SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 24 24"},(0,e.createElement)(c.Path,{d:"M12 3.2c-4.8 0-8.8 3.9-8.8 8.8 0 4.8 3.9 8.8 8.8 8.8 4.8 0 8.8-3.9 8.8-8.8 0-4.8-4-8.8-8.8-8.8zm0 16c-4 0-7.2-3.3-7.2-7.2C4.8 8 8 4.8 12 4.8s7.2 3.3 7.2 7.2c0 4-3.2 7.2-7.2 7.2zM11 17h2v-6h-2v6zm0-8h2V7h-2v2z"})),E=t=>(0,e.createElement)(o.BaseControl,null,(0,e.createElement)("div",null,0===t.logs.length?(0,e.createElement)("p",null,(0,i.__)("No logs available","teams-publisher")):(0,e.createElement)("ul",{className:"mstp_logs"},t.logs.map(((t,n)=>{return(0,e.createElement)("li",{key:`${t.date}-${n}`,className:t.type},(0,e.createElement)(o.Tooltip,{text:t.type},(0,e.createElement)(w,{size:"14",icon:(l=t.type,"success"===l?m:"error"===l?d:null)})),(0,e.createElement)(o.Tooltip,{text:t.date},(0,e.createElement)(w,{size:"14",icon:p})),(0,e.createElement)(o.Tooltip,{text:t.message},(0,e.createElement)(w,{size:"14",icon:u})),"    ",t.channel);var l}))))),g=(window.wp.editPost,window.wp.plugins);!function(t){const{PluginSidebar:n}=t.editPost,{useSelect:l,dispatch:a}=t.data;(0,g.registerPlugin)("teams-publisher-sidebar",{render:()=>{const[t,s]=(0,e.useState)([]),[c,m]=(0,e.useState)(!1),[d,h]=(0,e.useState)([]),w=l((e=>{const t=e("core/editor").getEditedPostAttribute("meta"),n=t?t.mstp_logs:[];return Array.isArray(n)?n:[]})),p=l((e=>e("core/editor").getEditedPostAttribute("status"))),u=l((e=>e("core/editor").getCurrentPostId()));(0,e.useEffect)((()=>{h(w)}),[w]);const g=(0,e.useCallback)((e=>{s(e)}),[]);return"publish"!==p?null:(0,e.createElement)(n,{name:"teams-publisher-sidebar",title:"Teams Publisher",icon:"megaphone"},(0,e.createElement)(o.PanelBody,{title:"Channels",initialOpen:!0},0===mstp_sidebar.channels.length&&(0,e.createElement)("p",null,(0,i.__)("Please register a channel","teams-publisher")),(0,e.createElement)(r,{metaKey:"mstp_channels",label:(0,i.__)("Select channels","teams-publisher"),options:mstp_sidebar.channels,channels:g}),(0,e.createElement)("hr",null),(0,e.createElement)(o.Flex,null,(0,e.createElement)(o.FlexItem,null,(0,e.createElement)(o.Button,{variant:"primary",icon:"megaphone",onClick:async()=>{if(0===t.length)alert((0,i.__)("No channel selected!","teams-publisher"));else{m(!0);try{await a("core/editor").savePost();const e=await fetch(mstp_sidebar.url,{method:"POST",headers:{"Content-Type":"application/json"},body:JSON.stringify({channels:t,post_id:u})}),n=await e.json();m(!1),(e=>{h(e),console.log(e)})(n)}catch(e){console.error("ERROR",e),m(!1)}}},isBusy:c},(0,i.__)("Publish","teams-publisher"))),(0,e.createElement)(o.FlexItem,null,(0,e.createElement)(o.Button,{variant:"secondary",onClick:()=>{window.location.href=mstp_sidebar.url_settings}},(0,i.__)("Add Channel","teams-publisher"))))),(0,e.createElement)(o.PanelBody,{title:"Logs",initialOpen:!0},(0,e.createElement)(E,{logs:d})))}})}(window.wp)})();
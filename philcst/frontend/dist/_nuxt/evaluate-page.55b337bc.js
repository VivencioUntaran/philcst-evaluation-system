import{_ as m}from"./EvaluationComponent.708098fd.js";import{u as f}from"./useAPI.05844c13.js";import{_ as h,r as p,q as g,c as n,x as l,a as t,z as k,C as x,I as S,F as b,D as E,H as i,E as u,o as e,t as I,p as T,f as B}from"./entry.b4168997.js";import"./DepartmentSelector.04ab34fc.js";const c=o=>(T("data-v-1dccae2e"),o=o(),B(),o),D={key:0},P={class:"evaluate-page__select-container"},q=c(()=>t("label",{for:""},"Select Evaluation Type",-1)),C=c(()=>t("option",{value:"",disabled:"",selected:""}," Select Evaluation Type ",-1)),F=["value"],V={key:1,class:"spinner-border text-primary",role:"status"},w=c(()=>t("span",{class:"visually-hidden"},"Loading...",-1)),L=[w],M={__name:"evaluate-page",setup(o){f();const r=p(!1),a=p(""),v=[{label:"Dean to Faculty",value:"dean-to-faculty"},{label:"Peer to Peer",value:"peer-to-peer"}];return g(async()=>{r.value=!0}),(N,_)=>{const d=m;return e(),n("div",null,[l(r)?(e(),n("div",D,[t("div",P,[q,k(t("select",{"onUpdate:modelValue":_[0]||(_[0]=s=>S(a)?a.value=s:null)},[C,(e(),n(b,null,E(v,(s,y)=>t("option",{key:y,value:s.value},I(s.label),9,F)),64))],512),[[x,l(a)]])]),l(a)==="dean-to-faculty"?(e(),i(d,{key:0,questionnaireType:"dean-to-faculty"})):u("",!0),l(a)==="peer-to-peer"?(e(),i(d,{key:1,questionnaireType:"peer-to-peer"})):u("",!0)])):(e(),n("div",V,L))])}}},U=h(M,[["__scopeId","data-v-1dccae2e"]]);export{U as default};

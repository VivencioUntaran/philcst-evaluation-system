import{u as b}from"./useAPI.05844c13.js";import{_ as g,L as w,v as C,q as S,o as t,c as a,x as n,B as p,z as x,C as D,a as c,t as m,E as k,F as B,D as V,I as A,p as I,f as E}from"./entry.b4168997.js";const _=e=>(I("data-v-85075987"),e=e(),E(),e),L=_(()=>c("label",{for:"",class:"fw-medium"}," Select department ",-1)),z=["disabled"],F=["disabled"],M=["value"],N={key:1},P=_(()=>c("p",null,"Loading...",-1)),T=[P],j={__name:"DepartmentSelector",props:{modelValue:{},showAll:{type:Boolean,default:!0},disabled:{type:Boolean,default:!1},allPlaceholder:{type:String,default:"All Departments"},disabledAll:{type:Boolean,default:!1},customClass:{type:String,default:""}},emits:["update:modelValue","valueChanged"],setup(e,{emit:r}){const i=e,f=b(),d=w({get(){return i.modelValue},set(l){r("update:modelValue",l)}}),s=C({academicYears:[],loaded:!1,disabled:!1});async function h(){const l=await f({url:"departments",method:"GET",headers:{"Content-Type":"application/json"}});s.departments=l.data.records}function v(){r("valueChanged")}return S(async()=>{await h(),s.loaded=!0}),(l,u)=>(t(),a("div",{class:p(["department-selector w-100",`department-selector--${e.customClass}`])},[n(s).loaded?(t(),a("div",{key:0,class:p(["d-flex w-100 flex-column","department-selector__dropdown-container",i.disabled&&"no-click disabled"])},[L,x(c("select",{name:"department","onUpdate:modelValue":u[0]||(u[0]=o=>A(d)?d.value=o:null),onChange:v,disabled:e.disabled},[e.showAll?(t(),a("option",{key:0,value:"",selected:"",disabled:e.disabledAll},m(e.allPlaceholder),9,F)):k("",!0),(t(!0),a(B,null,V(n(s).departments,(o,y)=>(t(),a("option",{key:y,value:o.id},m(o.department),9,M))),128))],40,z),[[D,n(d)]])],2)):(t(),a("div",N,T))],2))}},R=g(j,[["__scopeId","data-v-85075987"]]);export{R as _};
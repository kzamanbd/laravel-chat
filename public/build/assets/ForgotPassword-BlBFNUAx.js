import{d,T as u,c,w as r,o as m,a,u as s,Z as p,g as f,t as _,h as w,b as t,e as g,n as y,f as b}from"./app-sdvqNPVD.js";import{_ as k}from"./GuestLayout.vue_vue_type_script_setup_true_lang-D3uOjGXr.js";import{_ as h,a as x}from"./TextInput.vue_vue_type_script_setup_true_lang-BcogYgET.js";import{_ as V}from"./InputLabel.vue_vue_type_script_setup_true_lang-C3Zn1KCa.js";import{P as v}from"./PrimaryButton-D-XAMJGo.js";const B=t("div",{class:"mb-4 text-sm text-gray-600"}," Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one. ",-1),N={key:0,class:"mb-4 font-medium text-sm text-green-600"},P={class:"flex items-center justify-end mt-4"},j=d({__name:"ForgotPassword",props:{status:{}},setup(C){const e=u({email:""}),i=()=>{e.post(route("password.email"))};return(o,l)=>(m(),c(k,null,{default:r(()=>[a(s(p),{title:"Forgot Password"}),B,o.status?(m(),f("div",N,_(o.status),1)):w("",!0),t("form",{onSubmit:b(i,["prevent"])},[t("div",null,[a(V,{for:"email",value:"Email"}),a(h,{id:"email",type:"email",class:"mt-1 block w-full",modelValue:s(e).email,"onUpdate:modelValue":l[0]||(l[0]=n=>s(e).email=n),required:"",autofocus:"",autocomplete:"username"},null,8,["modelValue"]),a(x,{class:"mt-2",message:s(e).errors.email},null,8,["message"])]),t("div",P,[a(v,{class:y({"opacity-25":s(e).processing}),disabled:s(e).processing},{default:r(()=>[g(" Email Password Reset Link ")]),_:1},8,["class","disabled"])])],32)]),_:1}))}});export{j as default};
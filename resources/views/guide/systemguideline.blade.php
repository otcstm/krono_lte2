@extends('adminlte::page')

@section('title', 'Guideline')

@section('content')
        <h1>System Guideline</h1>
        <br>

        <div class="guide-system">
            <div class="row">
                <div class="col-md-2 guide-system-inside text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="37.862" height="45.808" viewBox="0 0 37.862 45.808">
                        <g id="Group_3167" data-name="Group 3167" transform="translate(-166.999 -67.913)">
                            <g id="approved" transform="translate(122.999 68)">
                            <path id="Path_1831" data-name="Path 1831" d="M88.291,53.935h-32.5A1.786,1.786,0,0,1,54,52.149V11.786A1.786,1.786,0,0,1,55.786,10h32.5a1.786,1.786,0,0,1,1.786,1.786V52.149A1.786,1.786,0,0,1,88.291,53.935Z" transform="translate(-9.107 -9.107)" fill="#fff"/>
                            <path id="Path_1844" data-name="Path 1844" d="M11,0A11,11,0,1,1,0,11,11,11,0,0,1,11,0Z" transform="translate(51.931 4.86)" fill="#f1f1f3"/>
                            <path id="Path_1832" data-name="Path 1832" d="M44.893,168.786a.894.894,0,1,1,.631-.262A.9.9,0,0,1,44.893,168.786Z" transform="translate(0 -167.087)"/>
                            <path id="Path_1833" data-name="Path 1833" d="M79.184,45.721h-32.5A2.682,2.682,0,0,1,44,43.042V4.14a.893.893,0,1,1,1.786,0v38.9a.894.894,0,0,0,.893.893h32.5a.894.894,0,0,0,.893-.893V2.679a.894.894,0,0,0-.893-.893H48.116a.893.893,0,1,1,0-1.786H79.184a2.682,2.682,0,0,1,2.679,2.679V43.042A2.682,2.682,0,0,1,79.184,45.721Z"/>
                            <path id="Path_1834" data-name="Path 1834" d="M223.965,121.734v.871a3.483,3.483,0,0,1-3.483,3.483h0A3.483,3.483,0,0,1,217,122.6v-.871a3.483,3.483,0,0,1,3.483-3.483h0A3.483,3.483,0,0,1,223.965,121.734Z" transform="translate(-157.551 -107.691)" fill="#ffcdac"/>
                            <path id="Path_1835" data-name="Path 1835" d="M179.73,206h0a8.038,8.038,0,0,0-7.479,5.094,10.975,10.975,0,0,0,14.957,0A8.038,8.038,0,0,0,179.73,206Z" transform="translate(-116.798 -187.605)" fill="#143a8c"/>
                            <path id="Path_1836" data-name="Path 1836" d="M136.421,40a12.02,12.02,0,0,0-1.815.138.893.893,0,0,0,.271,1.765,10.215,10.215,0,0,1,1.544-.117,10.085,10.085,0,0,1,7.718,16.584,8.947,8.947,0,0,0-4.662-3.9,4.362,4.362,0,0,0,1.32-3.127V50.47a4.376,4.376,0,1,0-8.751,0v.871a4.362,4.362,0,0,0,1.32,3.127,8.949,8.949,0,0,0-4.671,3.917,10.058,10.058,0,0,1-2.224-4.827.893.893,0,0,0-1.761.3A11.878,11.878,0,1,0,136.421,40Zm-2.59,11.341V50.47a2.59,2.59,0,1,1,5.179,0v.871a2.59,2.59,0,1,1-5.179,0Zm-3.812,8.351a7.143,7.143,0,0,1,12.8-.015,10.107,10.107,0,0,1-12.8.015Z" transform="translate(-73.49 -36.428)"/>
                            <path id="Path_1837" data-name="Path 1837" d="M209.93,399.788H197.25a.893.893,0,1,1,0-1.786h12.68a.893.893,0,1,1,0,1.786Z" transform="translate(-138.752 -362.461)"/>
                            <path id="Path_1838" data-name="Path 1838" d="M154.533,343.786a.893.893,0,1,1,.631-.262A.9.9,0,0,1,154.533,343.786Z" transform="translate(-99.849 -311.46)"/>
                            <path id="Path_1839" data-name="Path 1839" d="M154.533,399.786a.893.893,0,1,1,.631-.262A.9.9,0,0,1,154.533,399.786Z" transform="translate(-99.849 -362.459)"/>
                            <path id="Path_1840" data-name="Path 1840" d="M209.93,343.788H197.25a.893.893,0,1,1,0-1.786h12.68a.893.893,0,1,1,0,1.786Z" transform="translate(-138.752 -311.462)"/>
                            </g>
                        </g>
                    </svg>
                    <h4 style="color: #143A8C; font-weight: bold; margin-top: 0px;">Applicant</h4>
                </div>
                <div class="col-md-6 col-md-offset-1">
                        <h4 style="color: #143A8C; font-weight: bold">Get Started</h4>
                        <!-- <p style=" margin: 0">• How to start/end overtime?</p> -->
                        <!-- <p style=" margin: 0">• How to apply new overtime claim and submit for approval?</p>
                        <p style=" margin: 0">• How to change current work schedule?</p> -->
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideUser_01SEndOvertime.pdf")}}" target="_blank">• Start and end overtime (to capture clocking time for overtime)</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideUser_02ApplyClaim.pdf")}}" target="_blank">• Apply new overtime claim and submit for approval</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideUser_03ChangeWsc.pdf")}}" target="_blank">• Change current work schedule (Not applicable for shift employee)</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideUser_04ViewCalendar.pdf")}}" target="_blank">• View own calendar and work team calendar (For shift employee only)</a></p>

                </div>
                <!-- <div class="col-md-2 col-md-offset-1 guide-system-inside text-center flex">
                    <img src="{{asset("/vendor/images/guide-video.png")}}">
                </div> -->
            </div>
        </div>

        <div class="guide-system">
            <div class="row">
                <div class="col-md-2 guide-system-inside text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50.417" height="46.944" viewBox="0 0 50.417 46.944">
                    <g id="verified-text-paper" transform="translate(0 -1.777)">
                        <g id="Group_3168" data-name="Group 3168" transform="translate(0 0.778)">
                        <path id="Path_1846" data-name="Path 1846" d="M25.637,6.794H6.809a2.095,2.095,0,0,0,0,4.19H25.635a2.095,2.095,0,0,0,0-4.19Z" transform="translate(2.785 2.771)" fill="#143a8c"/>
                        <path id="Path_1847" data-name="Path 1847" d="M25.637,11.187H6.809a2.1,2.1,0,0,0,0,4.191H25.635a2.1,2.1,0,0,0,0-4.191Z" transform="translate(2.785 5.363)" fill="#143a8c"/>
                        <path id="Path_1848" data-name="Path 1848" d="M25.637,15.581H6.809a2.042,2.042,0,1,0,0,4.083H25.635a2.042,2.042,0,1,0,0-4.083Z" transform="translate(2.785 7.956)" fill="#143a8c"/>
                        <path id="Path_1849" data-name="Path 1849" d="M4.719,21.816a2.045,2.045,0,0,0,2.089,2.04h7.025A8.474,8.474,0,0,1,15.2,21.5a7.43,7.43,0,0,1,2.245-1.736H6.81A2.057,2.057,0,0,0,4.719,21.816Z" transform="translate(2.784 10.426)" fill="#143a8c"/>
                        <path id="Path_1850" data-name="Path 1850" d="M18.458,19.771H16.887a5.739,5.739,0,0,1,1.456.971l2.086,1.786a1.981,1.981,0,0,0,.121-.695A2.075,2.075,0,0,0,18.458,19.771Z" transform="translate(9.963 10.428)" fill="#143a8c"/>
                        <path id="Path_1852" data-name="Path 1852" d="M77.824,45.721H46.575A2.632,2.632,0,0,1,44,43.042V4.14a.859.859,0,1,1,1.717,0v38.9a.877.877,0,0,0,.858.893H77.824a.877.877,0,0,0,.858-.893V2.679a.877.877,0,0,0-.858-.893H47.957A.876.876,0,0,1,47.1.893.876.876,0,0,1,47.957,0H77.824A2.632,2.632,0,0,1,80.4,2.679V43.042A2.632,2.632,0,0,1,77.824,45.721Z" transform="translate(-44 1.086)"/>
                        <path id="Path_1851" data-name="Path 1851" d="M41.751,16.574a3.654,3.654,0,0,0-5.161.413l-12.027,14.1-6.012-5.164a3.661,3.661,0,1,0-4.77,5.555l8.8,7.556a3.686,3.686,0,0,0,2.385.886c.1,0,.189,0,.289-.013a3.663,3.663,0,0,0,2.5-1.274l14.415-16.9A3.662,3.662,0,0,0,41.751,16.574Z" transform="translate(7.377 8.024)" fill="#ef7202"/>
                        <path id="Path_1853" data-name="Path 1853" d="M44.893,168.786a.894.894,0,1,1,.631-.262A.9.9,0,0,1,44.893,168.786Z" transform="translate(-44 -166.001)"/>
                        </g>
                    </g>
                    </svg>
                    <h4 style="color: #143A8C; font-weight: bold; margin-top: 0px;">Verifier</h4>
                </div>
                <div class="col-md-6 col-md-offset-1">
                        <h4 style="color: #143A8C; font-weight: bold">Get Started</h4>
                        <!-- <p style=" margin: 0">• How to verify/query overtime claim?</p>
                        <p style=" margin: 0">• How to view claim verification report?</p> -->
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideVerifier_01VerifyClaim.pdf")}}" target="_blank">• Verify overtime claim</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideVerifier_02VerifyQuery.pdf")}}" target="_blank">• Query overtime claim</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideVerifier_03ClaimReport.pdf")}}" target="_blank">• View claim verification report</a></p>
                </div>
                <!-- <div class="col-md-2 col-md-offset-1 guide-system-inside text-center flex">
                    <img src="{{asset("/vendor/images/guide-video.png")}}">
                </div> -->
            </div>
        </div>

        <div class="guide-system">
            <div class="row">
                <div class="col-md-2 guide-system-inside text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="37.862" height="45.721" viewBox="0 0 37.862 45.721">
                        <g id="Group_3166" data-name="Group 3166" transform="translate(-165.999 -69.14)">
                            <g id="approved" transform="translate(121.999 69.14)">
                            <path id="Path_1831" data-name="Path 1831" d="M88.291,53.935h-32.5A1.786,1.786,0,0,1,54,52.149V11.786A1.786,1.786,0,0,1,55.786,10h32.5a1.786,1.786,0,0,1,1.786,1.786V52.149A1.786,1.786,0,0,1,88.291,53.935Z" transform="translate(-9.107 -9.107)" fill="#fff"/>
                            <path id="Path_1844" data-name="Path 1844" d="M11,0A11,11,0,1,1,0,11,11,11,0,0,1,11,0Z" transform="translate(51.931 4.86)" fill="#f1f1f3"/>
                            <path id="Path_1832" data-name="Path 1832" d="M44.893,168.786a.894.894,0,1,1,.631-.262A.9.9,0,0,1,44.893,168.786Z" transform="translate(0 -152.087)"/>
                            <path id="Path_1833" data-name="Path 1833" d="M79.184,45.721h-32.5A2.682,2.682,0,0,1,44,43.042V19.14a.893.893,0,0,1,1.786,0v23.9a.894.894,0,0,0,.893.893h32.5a.894.894,0,0,0,.893-.893V2.679a.894.894,0,0,0-.893-.893H61.116a.893.893,0,1,1,0-1.786H79.184a2.682,2.682,0,0,1,2.679,2.679V43.042A2.682,2.682,0,0,1,79.184,45.721Z"/>
                            <path id="Path_1834" data-name="Path 1834" d="M223.965,121.734v.871a3.483,3.483,0,0,1-3.483,3.483h0A3.483,3.483,0,0,1,217,122.6v-.871a3.483,3.483,0,0,1,3.483-3.483h0A3.483,3.483,0,0,1,223.965,121.734Z" transform="translate(-157.551 -107.691)" fill="#ffcdac"/>
                            <path id="Path_1835" data-name="Path 1835" d="M179.73,206h0a8.038,8.038,0,0,0-7.479,5.094,10.975,10.975,0,0,0,14.957,0A8.038,8.038,0,0,0,179.73,206Z" transform="translate(-116.798 -187.605)" fill="#143a8c"/>
                            <path id="Path_1836" data-name="Path 1836" d="M136.421,40a12.02,12.02,0,0,0-1.815.138.893.893,0,0,0,.271,1.765,10.215,10.215,0,0,1,1.544-.117,10.085,10.085,0,0,1,7.718,16.584,8.947,8.947,0,0,0-4.662-3.9,4.362,4.362,0,0,0,1.32-3.127V50.47a4.376,4.376,0,1,0-8.751,0v.871a4.362,4.362,0,0,0,1.32,3.127,8.949,8.949,0,0,0-4.671,3.917,10.058,10.058,0,0,1-2.224-4.827.893.893,0,0,0-1.761.3A11.878,11.878,0,1,0,136.421,40Zm-2.59,11.341V50.47a2.59,2.59,0,1,1,5.179,0v.871a2.59,2.59,0,1,1-5.179,0Zm-3.812,8.351a7.143,7.143,0,0,1,12.8-.015,10.107,10.107,0,0,1-12.8.015Z" transform="translate(-73.49 -36.428)"/>
                            <path id="Path_1837" data-name="Path 1837" d="M209.93,399.788H197.25a.893.893,0,1,1,0-1.786h12.68a.893.893,0,1,1,0,1.786Z" transform="translate(-138.752 -362.461)"/>
                            <path id="Path_1838" data-name="Path 1838" d="M154.533,343.786a.893.893,0,1,1,.631-.262A.9.9,0,0,1,154.533,343.786Z" transform="translate(-99.849 -311.46)"/>
                            <path id="Path_1839" data-name="Path 1839" d="M154.533,399.786a.893.893,0,1,1,.631-.262A.9.9,0,0,1,154.533,399.786Z" transform="translate(-99.849 -362.459)"/>
                            <path id="Path_1840" data-name="Path 1840" d="M209.93,343.788H197.25a.893.893,0,1,1,0-1.786h12.68a.893.893,0,1,1,0,1.786Z" transform="translate(-138.752 -311.462)"/>
                            <path id="Path_1843" data-name="Path 1843" d="M6.5,0A6.5,6.5,0,1,1,0,6.5,6.5,6.5,0,0,1,6.5,0Z" transform="translate(44.931 0.86)" fill="#ef7202"/>
                            <path id="Path_1841" data-name="Path 1841" d="M89.234,60.026a.89.89,0,0,1-.631-.262L86.978,58.14a.893.893,0,0,1,1.263-1.263l.994.994,2.908-2.908a.893.893,0,0,1,1.263,1.263l-3.54,3.54A.891.891,0,0,1,89.234,60.026Z" transform="translate(-38.902 -49.816)"/>
                            <path id="Path_1842" data-name="Path 1842" d="M51.322,14.645a7.322,7.322,0,1,1,7.322-7.322A7.331,7.331,0,0,1,51.322,14.645Zm0-12.859a5.536,5.536,0,1,0,5.536,5.536A5.543,5.543,0,0,0,51.322,1.786Z"/>
                            </g>
                        </g>
                    </svg>

                    <h4 style="color: #143A8C; font-weight: bold; margin-top: 0px;">Approver</h4>
                </div>
                <div class="col-md-6 col-md-offset-1">
                        <h4 style="color: #143A8C; font-weight: bold">Get Started</h4>
                        <!-- <p style=" margin: 0">• How to set default verifier?</p>
                        <p style=" margin: 0">• How to approve/query overtime claim?</p>
                        <p style=" margin: 0">• How to view claim approval report?</p> -->
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideApprver_01SetVerifier.pdf")}}" target="_blank">• Set default verifier (Optional)</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideApprver_02SetSpecVerifier.pdf")}}" target="_blank">• Assign specific verifier to specific overtime claim (Optional)</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideApprver_03ApproveClaim.pdf")}}" target="_blank">• Approve overtime claim</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideApprver_04QueryClaim.pdf")}}" target="_blank">• Query overtime claim</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideApprver_05ViewClaim.pdf")}}" target="_blank">• View claim approval report</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideApprver_06CRWorkSche.pdf")}}" target="_blank">• Approve/reject change request of work schedule</a></p>

                </div>
                <!-- <div class="col-md-2 col-md-offset-1 guide-system-inside text-center flex">
                    <img src="{{asset("/vendor/images/guide-video.png")}}">
                </div> -->
            </div>
        </div>


        <div class="guide-system">
            <div class="row">
                <div class="col-md-2 guide-system-inside text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="49.7" height="42.222" viewBox="0 0 49.7 42.222">
                        <g id="Group_3173" data-name="Group 3173" transform="translate(-165.206 -75)">
                            <path id="Path_1858" data-name="Path 1858" d="M18.714,0A18.714,18.714,0,1,1,0,18.714,18.714,18.714,0,0,1,18.714,0Z" transform="translate(166.439 77.193)" fill="#f1f1f3"/>
                            <path id="Path_1859" data-name="Path 1859" d="M228.85,124.176v1.481a5.925,5.925,0,0,1-5.925,5.925h0A5.925,5.925,0,0,1,217,125.657v-1.481a5.925,5.925,0,0,1,5.925-5.925h0A5.925,5.925,0,0,1,228.85,124.176Z" transform="translate(-37.771 -31.363)" fill="#ffcdac"/>
                            <path id="Path_1860" data-name="Path 1860" d="M184.974,206h0a13.674,13.674,0,0,0-12.723,8.667,18.671,18.671,0,0,0,25.446,0A13.674,13.674,0,0,0,184.974,206Z" transform="translate(0.179 -105.782)" fill="#143a8c"/>
                            <path id="Path_1861" data-name="Path 1861" d="M144.643,40a20.448,20.448,0,0,0-3.088.235,1.519,1.519,0,0,0,.46,3,17.378,17.378,0,0,1,2.627-.2,17.158,17.158,0,0,1,13.13,28.214,15.221,15.221,0,0,0-7.931-6.637,7.421,7.421,0,0,0,2.245-5.321V57.813a7.444,7.444,0,1,0-14.888,0v1.481a7.421,7.421,0,0,0,2.245,5.321,15.224,15.224,0,0,0-7.947,6.664,17.112,17.112,0,0,1-3.784-8.211,1.519,1.519,0,0,0-3,.5A20.207,20.207,0,1,0,144.643,40Zm-4.406,19.294V57.813a4.406,4.406,0,1,1,8.811,0v1.481a4.406,4.406,0,1,1-8.811,0ZM133.752,73.5a12.151,12.151,0,0,1,21.77-.026,17.194,17.194,0,0,1-21.77.026Z" transform="translate(40.51 35)"/>
                            <g id="laptop" transform="translate(191.425 93.714)">
                            <path id="Path_1867" data-name="Path 1867" d="M23.48,326.857a1.978,1.978,0,0,1-1.976,1.976H1.977a1.978,1.978,0,0,1-1.85-2.67l1.82-4.853a1.985,1.985,0,0,1,1.85-1.282H19.684a1.985,1.985,0,0,1,1.85,1.282C23.523,326.615,23.48,326.39,23.48,326.857Z" transform="translate(0 -305.326)" fill="#999"/>
                            <path id="Path_1868" data-name="Path 1868" d="M33.054,325.214a1.971,1.971,0,0,1-1.494.685H12.033a1.971,1.971,0,0,1-1.494-.685l1.464-3.9a1.985,1.985,0,0,1,1.85-1.282H29.74a1.985,1.985,0,0,1,1.85,1.282Z" transform="translate(-10.056 -305.326)" fill="#aaa"/>
                            <path id="Path_1869" data-name="Path 1869" d="M67.105,2.23v8.8a2.2,2.2,0,0,1-2.2,2.2H50.23a2.2,2.2,0,0,1-2.2-2.2V2.23a2.2,2.2,0,0,1,2.2-2.2H64.9a2.2,2.2,0,0,1,2.2,2.2Z" transform="translate(-45.827)" fill="#c15c02"/>
                            <path id="Path_1870" data-name="Path 1870" d="M120.537,2.23v8.438H106.23a2.2,2.2,0,0,1-2.2-2.2V.029h14.307a2.2,2.2,0,0,1,2.2,2.2Z" transform="translate(-99.259)" fill="#ef7202"/>
                            <path id="Path_1871" data-name="Path 1871" d="M23.382,326.164l-1.82-4.853a1.985,1.985,0,0,0-1.85-1.282H3.825a1.985,1.985,0,0,0-1.85,1.282c-1.943,5.18-1.946,5.046-1.946,5.546A1.978,1.978,0,0,0,2,328.833H21.532a1.978,1.978,0,0,0,1.85-2.67Zm-1.85,1.2H2a.509.509,0,0,1-.509-.509c0-.131-.141.286,1.852-5.031a.511.511,0,0,1,.476-.33H19.712a.511.511,0,0,1,.476.33l1.82,4.853a.509.509,0,0,1-.476.687Z" transform="translate(-0.028 -305.326)"/>
                            <path id="Path_1872" data-name="Path 1872" d="M168.1,432.029h-7.337a.734.734,0,1,0,0,1.467H168.1a.734.734,0,0,0,0-1.467Z" transform="translate(-152.691 -412.19)"/>
                            <path id="Path_1873" data-name="Path 1873" d="M82.23,432.029H80.763a.734.734,0,0,0,0,1.467H82.23a.734.734,0,1,0,0-1.467Z" transform="translate(-76.359 -412.19)"/>
                            <path id="Path_1874" data-name="Path 1874" d="M386.23,432.029h-1.467a.734.734,0,1,0,0,1.467h1.467a.734.734,0,1,0,0-1.467Z" transform="translate(-366.419 -412.19)"/>
                            <circle id="Ellipse_61" data-name="Ellipse 61" cx="0.734" cy="0.734" r="0.734" transform="translate(11.007 16.904)"/>
                            <circle id="Ellipse_62" data-name="Ellipse 62" cx="0.734" cy="0.734" r="0.734" transform="translate(8.072 16.904)"/>
                            <circle id="Ellipse_63" data-name="Ellipse 63" cx="0.734" cy="0.734" r="0.734" transform="translate(5.137 16.904)"/>
                            <circle id="Ellipse_64" data-name="Ellipse 64" cx="0.734" cy="0.734" r="0.734" transform="translate(13.942 16.904)"/>
                            <circle id="Ellipse_65" data-name="Ellipse 65" cx="0.734" cy="0.734" r="0.734" transform="translate(16.876 16.904)"/>
                            <path id="Path_1875" data-name="Path 1875" d="M64.9.029H50.23a2.2,2.2,0,0,0-2.2,2.2v8.8a2.2,2.2,0,0,0,2.2,2.2H64.9a2.2,2.2,0,0,0,2.2-2.2V2.23a2.2,2.2,0,0,0-2.2-2.2Zm.734,11.006a.735.735,0,0,1-.734.734H50.23a.735.735,0,0,1-.734-.734V2.23A.735.735,0,0,1,50.23,1.5H64.9a.735.735,0,0,1,.734.734Z" transform="translate(-45.827)"/>
                            <path id="Path_1876" data-name="Path 1876" d="M384.763,114.964a.734.734,0,0,0,.734-.734v-1.467a.734.734,0,1,0-1.467,0v1.467A.734.734,0,0,0,384.763,114.964Z" transform="translate(-366.419 -106.864)"/>
                            </g>
                        </g>
                    </svg>
                    <h4 style="color: #143A8C; font-weight: bold; margin-top: 0px;">User Admin</h4>
                </div>
                <div class="col-md-6 col-md-offset-1">
                        <h4 style="color: #143A8C; font-weight: bold">Get Started</h4>
                        <!-- <p style=" margin: 0">• Shift Management</p>
                        <p style=" margin: 0">• Generate Reports</p> -->
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideUserADM_01ShiftManagement.pdf")}}" target="_blank">• End to end shift management process</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideUserADM_02GenerateReports.pdf")}}" target="_blank">• Generate Reports</a></p>
                </div>
                <!-- <div class="col-md-2 col-md-offset-1 guide-system-inside text-center flex">
                    <img src="{{asset("/vendor/images/guide-video.png")}}">
                </div> -->
            </div>
        </div>

        <div class="guide-system">
            <div class="row">
                <div class="col-md-2 guide-system-inside text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="64.357" height="44.157" viewBox="0 0 64.357 44.157">
                <g id="Group_3174" data-name="Group 3174" transform="translate(-155.954 -71.256)">
                    <g id="admin-with-cogwheels" transform="translate(142.322 76.479) rotate(-8)">
                    <path id="Path_1863" data-name="Path 1863" d="M92.727,73.928a5.007,5.007,0,0,0-3.1,8.935,10.842,10.842,0,0,1,7.168-6.84A5.011,5.011,0,0,0,92.727,73.928Z" transform="translate(-66.019 -66.005)" fill="#143a8c"/>
                    <path id="Path_1864" data-name="Path 1864" d="M17,15.927a10.418,10.418,0,0,0,.734,1.762L16.2,19.232a1.063,1.063,0,0,0,0,1.5l2.719,2.72a1.067,1.067,0,0,0,1.5,0l1.541-1.544c.231.122.488.2.727.3,0-.018,0-.036,0-.053a15.822,15.822,0,0,1,.4-3.569A6.709,6.709,0,1,1,32.54,9.657a11.7,11.7,0,0,1,3.791.111,10.127,10.127,0,0,0-.656-1.574L37.219,6.65a1.057,1.057,0,0,0,0-1.5L34.5,2.43a1.059,1.059,0,0,0-1.5,0L31.454,3.974a9.941,9.941,0,0,0-1.762-.731V1.061A1.059,1.059,0,0,0,28.631,0H24.785a1.063,1.063,0,0,0-1.062,1.061V3.243a10.047,10.047,0,0,0-1.762.731L20.418,2.432a1.062,1.062,0,0,0-1.5,0L16.2,5.149a1.066,1.066,0,0,0,0,1.5l1.543,1.544a10.158,10.158,0,0,0-.731,1.762h-2.18a1.062,1.062,0,0,0-1.062,1.063v3.846a1.062,1.062,0,0,0,1.062,1.062Z" fill="#ef7202"/>
                    <path id="Path_1865" data-name="Path 1865" d="M289.526,255.057a5.464,5.464,0,1,0-5.507-9.431,19.754,19.754,0,0,1-2.337,5.594A16.429,16.429,0,0,1,289.526,255.057Z" transform="translate(9.34 -348.921) rotate(42)" fill="#143a8c"/>
                    <path id="Path_1866" data-name="Path 1866" d="M321.133,177.541a1.146,1.146,0,0,0-1.145-1.146h-2.353a10.868,10.868,0,0,0-.792-1.9l1.666-1.666a1.144,1.144,0,0,0,0-1.62l-2.932-2.933a1.143,1.143,0,0,0-1.619,0l-1.669,1.667a10.7,10.7,0,0,0-1.9-.79V166.8a1.144,1.144,0,0,0-1.143-1.145h-4.149a1.129,1.129,0,0,0-.722.276,15.6,15.6,0,0,1,.541,4.132,19.859,19.859,0,0,1-.21,2.832,7.093,7.093,0,0,1,2.465-.463,7.177,7.177,0,0,1,3.881,13.222,16.392,16.392,0,0,1,3.34,5.57,1.174,1.174,0,0,0,.379.073,1.144,1.144,0,0,0,.81-.335l2.934-2.933a1.152,1.152,0,0,0,0-1.62l-1.669-1.667a10.827,10.827,0,0,0,.792-1.9h2.353a1.148,1.148,0,0,0,1.147-1.146Z" transform="translate(-52.989 -309.972) rotate(42)" fill="#ef7202"/>
                    </g>
                    <path id="Path_1854" data-name="Path 1854" d="M18.714,0A18.714,18.714,0,1,1,0,18.714,18.714,18.714,0,0,1,18.714,0Z" transform="translate(166.439 77.193)" fill="#f1f1f3"/>
                    <path id="Path_1855" data-name="Path 1855" d="M228.85,124.176v1.481a5.925,5.925,0,0,1-5.925,5.925h0A5.925,5.925,0,0,1,217,125.657v-1.481a5.925,5.925,0,0,1,5.925-5.925h0A5.925,5.925,0,0,1,228.85,124.176Z" transform="translate(-37.772 -31.363)" fill="#ffcdac"/>
                    <path id="Path_1856" data-name="Path 1856" d="M184.974,206h0a13.674,13.674,0,0,0-12.723,8.667,18.671,18.671,0,0,0,25.446,0A13.674,13.674,0,0,0,184.974,206Z" transform="translate(0.179 -105.782)" fill="#143a8c"/>
                    <path id="Path_1857" data-name="Path 1857" d="M144.643,40a20.448,20.448,0,0,0-3.088.235,1.519,1.519,0,0,0,.46,3,17.378,17.378,0,0,1,2.627-.2,17.158,17.158,0,0,1,13.13,28.214,15.221,15.221,0,0,0-7.931-6.637,7.421,7.421,0,0,0,2.245-5.321V57.813a7.444,7.444,0,1,0-14.888,0v1.481a7.421,7.421,0,0,0,2.245,5.321,15.224,15.224,0,0,0-7.947,6.664,17.112,17.112,0,0,1-3.784-8.211,1.519,1.519,0,0,0-3,.5A20.207,20.207,0,1,0,144.643,40Zm-4.406,19.294V57.813a4.406,4.406,0,1,1,8.811,0v1.481a4.406,4.406,0,1,1-8.811,0ZM133.752,73.5a12.151,12.151,0,0,1,21.77-.026,17.194,17.194,0,0,1-21.77.026Z" transform="translate(40.51 35)"/>
                </g>
                </svg>

                    <h4 style="color: #143A8C; font-weight: bold; margin-top: 0px;">System/Super Admin</h4>
                </div>
                <div class="col-md-6 col-md-offset-1">
                        <h4 style="color: #143A8C; font-weight: bold">Get Started</h4>
                        <!-- <p style=" margin: 0">• Maintain all configuration</p>
                        <p style=" margin: 0">• Generate Reports</p> -->
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideSysADM_01Configuration.pdf")}}" target="_blank">• Maintain all configuration/setting</a></p>
                        <p style=" margin: 0"><a href="{{asset("/vendor/docs/GuideSysADM_02GenerateReports.pdf")}}" target="_blank">• Generate Reports</a></p>
        </div>
                <!-- <div class="col-md-2 col-md-offset-1 guide-system-inside text-center flex">
                    <img src="{{asset("/vendor/images/guide-video.png")}}">
                </div> -->
            </div>
        </div>
@stop

@section('js')
<script type="text/javascript">
</script>
@stop

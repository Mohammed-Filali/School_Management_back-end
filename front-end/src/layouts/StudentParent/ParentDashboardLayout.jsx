import {Link, Outlet, useNavigate} from "react-router-dom";
import { LOGIN_ROUTE,PARENT_DUSHBOARD_ROUTE, RedirectRoute} from "../../router/index.jsx";
import {useEffect, useState,} from "react";
import {GaugeIcon} from "lucide-react";
import {ModeToggle} from "../../components/mode-toggle.jsx";
import {ParentAdministrationSideBar} from "../Administration/ParentAdministrationSideBar.jsx";
import ParentDropDownMenu from "./ParentDropDownMenu.jsx";
import { UseUserContext } from "../../context/StudentContext.jsx";
import { ParentApi } from "../../service/api/student/admins/parenpApi.js";

export default function ParentDashboardLayout() {
  const navigate = useNavigate()
  const [isLoading, setIsLoading] = useState(true);
      const { authenticated, setUser, setAuthenticated, logout: contextLogout  } = UseUserContext();

     useEffect(() => {
       if (authenticated) {
         setIsLoading(false);
         ParentApi.getUser()
           .then(( {data} ) => {
               setIsLoading(false)
                console.log(data.data);
                const {role} = data
                        if(role !== 'parent') {
                          navigate(RedirectRoute(role));
                        }
              setUser(data);
             setAuthenticated(true);
           })
           .catch((error) => {
             console.error("Failed to fetch user data:", error);

           });
       }else if (!authenticated){
           setIsLoading(false)
           contextLogout();
           navigate(LOGIN_ROUTE);

       }
     }, [authenticated]);


    if (isLoading) {
      return <div className="flex justify-center items-center h-screen">Loading...</div>;
    }

  return <>
    <header>
      <div
        className="items-center justify-between flex bg-opacity-90 px-12 py-4 mb-4 mx-auto">
        <div className="text-2xl text-white font-semibold inline-flex items-center  text-gray-900 dark:text-white">
           {/* <Logo /> */}
        </div>
        <div>
          <ul className="flex text-white place-items-center">
            <li className="ml-5 px-2 py-1">
              <Link className="flex items-center  text-gray-900 dark:text-white" to={PARENT_DUSHBOARD_ROUTE}><GaugeIcon className={'mx-1'}/>Dashboard</Link>
            </li>
            <li className="ml-5 px-2 py-1">
              <ParentDropDownMenu/>
            </li>
            <li className="ml-5 px-2 py-1">
              <ModeToggle/>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <hr/>
    <main className={'mx-auto px-10 space-y-4 py-4'}>
      <div className="flex">
        <div className={'w-full md:w-2/12 border mr-2 rounded-l'}>
          <ParentAdministrationSideBar/>
        </div>
        <div className={'w-full md:w-10/12 border rounded-l'}>
          <Outlet/>
        </div>
      </div>
    </main>
  </>
}

import { Link, Outlet, useNavigate } from "react-router-dom";
import { GaugeIcon } from "lucide-react";
import AdminDropDownManu from "./AdminDropDown";
import { STUDENT_DUSHBOARD_ROUTE, LOGIN_ROUTE, RedirectRoute } from "../../router";
import { AdminAdministrationSideBare  } from "../administration/AdminAdministrationSideBare ";
import { ModeToggle } from "../../components/mode-toggle";
import { useEffect, useState } from "react";
import { UseUserContext } from "../../context/StudentContext";
import { AdminApi } from "../../service/api/student/admins/adminApi";

export default function AdminnDashboardLayout() {
  const navigate = useNavigate();
  const [isLoading, setIsLoading] = useState(true);
  const { authenticated, setUser, setAuthenticated, logout: contextLogout } = UseUserContext();

  useEffect(() => {
    if (authenticated) {
      setIsLoading(false);
      AdminApi.getUser()
        .then(({ data }) => {
            setIsLoading(false)
            const {role} = data
                    if(role !== 'admin') {
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

  return (
    <>
      <header>
        <div className="flex items-center justify-between bg-opacity-90 px-12 py-4 mb-4 mx-auto h-100">
          <div className="text-2xl text-white font-semibold inline-flex items-center  text-gray-900 dark:text-white">
            Student Dashboard
          </div>
          <div>
            <ul className="flex text-white place-items-center">
              <li className="ml-5 px-2 py-1">
                <Link className="flex items-center  text-gray-900 dark:text-white " to={STUDENT_DUSHBOARD_ROUTE}>
                  <GaugeIcon className="mx-1 " />
                  Dashboard
                </Link>
              </li>
              <li className="ml-5 px-2 py-1 ">
                <AdminDropDownManu  />
              </li>
              <li className="ml-5 px-2 py-1">
                <ModeToggle />
              </li>
            </ul>
          </div>
        </div>
      </header>
      <hr />
      <main className="mx-auto px-10 space-y-4 py-4">
        <div className="flex">
          <div className="w-100 md:w-1/4">
            <AdminAdministrationSideBare  />
          </div>
          <div className="w-100 md:w-3/4">
            <Outlet />
          </div>
        </div>
      </main>
      {/* <footer className="bg-gray-800 text-white text-center py-4">
        © 2024 Your School Name. All rights reserved.
      </footer> */}
    </>
  );
}

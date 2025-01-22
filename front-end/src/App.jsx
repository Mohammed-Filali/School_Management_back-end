
import './App.css'
import { RouterProvider } from 'react-router-dom'
import {Router} from './router/index.jsx'
import StudentContext from './context/StudentContext.jsx'
import { ThemeProvider } from './components/theme-provider.jsx'
import { Toaster } from "@/components/ui/sonner"
import { ChakraProvider } from '@chakra-ui/react'
function App() {


  return (
    <>

        <StudentContext>
            <ThemeProvider defaultTheme="dark" storageKey="vite-ui-theme">
                <RouterProvider router={Router} />
            </ThemeProvider>

        </StudentContext>
        <Toaster/>
       
   </>
  )
}

export default App

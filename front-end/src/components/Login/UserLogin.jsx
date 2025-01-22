import { Link, useNavigate } from "react-router-dom";
import * as z from "zod";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { Button } from "@/components/ui/button";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Loader } from "lucide-react";
import { UseUserContext } from "../../context/StudentContext";
import {  RedirectRoute} from "../../router";



// Validation schema
const formSchema = z.object({
  email: z.string().email().max(50).min(2),
  password: z.string().min(8).max(30),
});

export default function UserLogin() {
  const { login, setAuthenticated, setToken   } = UseUserContext();
  const form = useForm({
    resolver: zodResolver(formSchema),
    defaultValues: {
      email: "mohammed@gmail.com",
      password: "123456789",
    },
  });

  const navigate = useNavigate();



const onSubmit =async (values)=>{
    try {
        const res = await login(values)
        if(res.success){
            setToken(res.token);

            setAuthenticated(true)
            const{role}= res.data ;
            navigate(RedirectRoute(role))
        }

    } catch (error) {
        if (error.response && error.response.status === 401) {
                  form.setError('email', {
                      message: 'Invalid credentials, please try again.'
                  });
              } else {
                  form.setError('email', {
                      message: 'An unexpected error occurred'
                  });
              }
        console.log(error);

    }
}
  return (
    <div className="flex items-center justify-center min-h-screen ">
        <div className="space-y-8 w-[30rem] p-8 shadow-lg rounded-lg">
        <h1 className="text-2xl font-bold text-center ">Login</h1>
    <Form {...form}>
      <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-8">
        <FormField
          control={form.control}
          name="email"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Email</FormLabel>
              <FormControl>
                <Input placeholder="Email" {...field} />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <FormField
          control={form.control}
          name="password"
          render={({ field }) => (
            <FormItem>
              <FormLabel>Password</FormLabel>
              <FormControl>
                <Input type="password" placeholder="Password" {...field} />
              </FormControl>
              <FormMessage />
            </FormItem>
          )}
        />
        <Button disabled={form.formState.isSubmitting} type="submit">
          {form.formState.isSubmitting && (
            <Loader className="animate-spin" />
          )}
          Submit
        </Button>
        <Link to="/forgot_Password" className="text-gray-600 hover:text-gray-800">forgot Password</Link>

      </form>
    </Form>
    </div>
    </div>
  );
}

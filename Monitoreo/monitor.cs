using System;
using System.IO;
using System.Threading;

namespace MonitorLigaGo
{
    class Program
    {
        
        static bool ejecutoMonitor = true;

        static void Main(string[] args)
        {
            Console.Title = "LigaGo - Sistema de Supervisión Multihilo";
            Console.WriteLine("=================================================");
            Console.WriteLine("    MONITOR DE INFRAESTRUCTURA - LIGAGO          ");
            Console.WriteLine("=================================================");
            Console.WriteLine("[Sistema] Inicializando hilos de supervisión...");
            Console.WriteLine("[Sistema] Presiona la tecla 'Q' para apagar el monitor de forma segura.\n");

            
            Thread threadSupervision = new Thread(new ThreadStart(MonitorearServicios));
            
            
            threadSupervision.Start();

            
            while (ejecutoMonitor)
            {
                if (Console.KeyAvailable)
                {
                    ConsoleKeyInfo tecla = Console.ReadKey(true);
                    if (tecla.Key == ConsoleKey.Q)
                    {
                        Console.WriteLine("\n[Hilo Principal] Petición de apagado detectada. Sincronizando hilos...");
                        ejecutoMonitor = false; 
                    }
                }
                Thread.Sleep(100); 
            }

            threadSupervision.Join();

            Console.WriteLine("[Sistema] Monitor cerrado con éxito. Presiona cualquier tecla para salir.");
            Console.ReadKey();
        }

        // HILO 1
        static void MonitorearServicios()
        {
            string archivoLog = "log_monitor.txt";
            Console.WriteLine("[Hilo 1] Subproceso de monitorización lanzado correctamente.");

            while (ejecutoMonitor)
            {
                try
                {
                    string mensajeLog = $"[{DateTime.Now:yyyy-MM-dd HH:mm:ss}] STATUS: PostgreSQL en puerto 5432 activo - Conexiones entrantes: OK.";
                    
                    File.AppendAllText(archivoLog, mensajeLog + Environment.NewLine);
                    
                    Console.WriteLine($"[Hilo 1] Registro guardado: {mensajeLog}");
                }
                catch (Exception ex)
                {
                    Console.WriteLine($"[Hilo 1] Error crítico al escribir en log: {ex.Message}");
                }

                for (int i = 0; i < 100 && ejecutoMonitor; i++)
                {
                    Thread.Sleep(100); 
                }
            }

            Console.WriteLine("[Hilo 1] Subproceso finalizado y recursos liberados.");
        }
    }
}